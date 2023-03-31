<?php

namespace FelipeMenezesDM\LaravelSecretManagerSuite\Suites;

use FelipeMenezesDM\LaravelLoggerAdapter\LogPayload;
use FelipeMenezesDM\LaravelCommons\Enums\HttpStatusCode;
use Aws\SecretsManager\SecretsManagerClient;
use Exception;

class AWSSuite extends Suite
{
    protected static $isCloud = true;

    /** @Override */
    public function getSecretData(string $secretName): string
    {
        if(empty($secretName)) {
            return $secretName;
        }elseif($cached = $this->getCache($secretName)) {
            return $cached;
        }

        try{
            $result = $this->getSecretsManagerClient()->getSecretValue([ 'SecretId' => $secretName ]);
            return $this->putCache($secretName, isset($result['SecretString']) ? $result['SecretString'] :  base64_decode($result['SecretBinary']));
        }catch(Exception $e) {
            error_log(json_encode(
                LogPayload::build()
                    ->setMessage($e->getMessage())
                    ->setLogCode($e->getCode())
                    ->setDetails($e->getTrace())
                    ->setHttpStatus(HttpStatusCode::HTTP_INTERNAL_SERVER_ERROR)
                    ->toArray()
            ));

            return "";
        }
    }

    /** @Override */
    public function createSecret(string $secretName, array|string $secretValue): void
    {
        if(!empty($secretName)) {
            try {
                if (is_array($secretValue)) {
                    $secretValue = json_encode($secretValue);
                }

                $client = $this->getSecretsManagerClient();

                try{
                    $client->createSecret([
                        'Description'   => 'Created by Auth Server',
                        'Name'          => $secretName,
                        'SecretString'  => $secretValue,
                    ]);
                }catch(Exception) {
                    $result = $client->getSecretValue([ 'SecretId' => $secretName ]);
                    $currentValue = isset($result['SecretString']) ? $result['SecretString'] :  base64_decode($result['SecretBinary']);

                    if($secretValue != $currentValue) {
                        $client->putSecretValue([
                            'SecretId' => $secretName,
                            'SecretString' => $secretValue,
                        ]);
                    }
                }
            }catch(Exception $e) {
                error_log(json_encode(
                    LogPayload::build()
                        ->setMessage($e->getMessage())
                        ->setLogCode($e->getCode())
                        ->setDetails($e->getTrace())
                        ->setHttpStatus(HttpStatusCode::HTTP_INTERNAL_SERVER_ERROR)
                        ->toArray()
                ));
            }
        }
    }

    /**
     * Get an AWS secret manager client.
     * @return SecretsManagerClient
     */
    private function getSecretsManagerClient() : SecretsManagerClient
    {
        return new SecretsManagerClient([
            'credentials'       => false,
            'version'           => 'latest',
            'region'            => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'endpoint'          => env('AWS_ENDPOINT'),
            'account'           => env('AWS_ACCOUNT_ID'),
        ]);
    }
}
