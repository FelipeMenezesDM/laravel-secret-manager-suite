<?php

namespace FelipeMenezesDM\LaravelSecretManagerSuite;

use FelipeMenezesDM\LaravelLoggerAdapter\LogPayload;
use FelipeMenezesDM\LaravelCommons\Enums\HttpStatusCode;
use Aws\SecretsManager\SecretsManagerClient;
use Exception;
use Illuminate\Support\Facades\Log;

class AWSSuite extends Suite
{
    protected static $isCloud = true;

    /** @Override */
    protected function getSecretData(string $secretName): string
    {
        if(empty($secretName)) {
            return $secretName;
        }

        try{
            $result = $this->getSecretsManagerClient()->getSecretValue([ 'SecretId' => $secretName ]);

            if(isset($result['SecretString'])) {
                return $result['SecretString'];
            }

            return base64_decode($result['SecretBinary']);
        }catch(Exception $e) {
            Log::info(sprintf('Secret query failed: %s', $secretName), LogPayload::build()
                ->setMessage($e->getMessage())
                ->setLogCode($e->getCode())
                ->setDetails($e->getTrace())
                ->setHttpStatus(HttpStatusCode::HTTP_INTERNAL_SERVER_ERROR)
                ->toArray()
            );

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
                    $client->putSecretValue([
                        'SecretId'      => $secretName,
                        'SecretString'  => $secretValue,
                    ]);
                }
            }catch(Exception $e) {
                Log::info(sprintf('Secret creation failed: %s', $secretName), LogPayload::build()
                    ->setMessage($e->getMessage())
                    ->setLogCode($e->getCode())
                    ->setDetails($e->getTrace())
                    ->setHttpStatus(HttpStatusCode::HTTP_INTERNAL_SERVER_ERROR)
                    ->toArray()
                );
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
