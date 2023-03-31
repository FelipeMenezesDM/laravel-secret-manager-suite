<?php

namespace FelipeMenezesDM\LaravelSecretManagerSuite\Suites;

use FelipeMenezesDM\LaravelLoggerAdapter\LogPayload;
use FelipeMenezesDM\LaravelCommons\Enums\HttpStatusCode;
use Google\Cloud\SecretManager\V1\Replication;
use Google\Cloud\SecretManager\V1\Secret;
use Google\Cloud\SecretManager\V1\SecretManagerServiceClient;
use Google\Cloud\SecretManager\V1\SecretPayload;
use Exception;

class GCPSuite extends Suite
{
    protected static $isCloud = true;

    /** @Override */
    public function getSecretData(string $secretName) : string
    {
        if(empty($secretName)) {
            return $secretName;
        }elseif($cached = $this->getCache($secretName)) {
            return $cached;
        }

        try {
            $secretFullName = 'projects/' . getenv('GCP_PROJECT_ID') . '/secrets/' . $secretName . '/versions/latest';
            return $this->putCache($secretName, (new SecretManagerServiceClient())->accessSecretVersion($secretFullName)->getPayload()->getData());
        } catch (Exception $e) {
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
    public function createSecret(string $secretName, array|string $secretValue) : void
    {
        if(!empty($secretName)) {
            try {
                if (is_array($secretValue)) {
                    $secretValue = json_encode($secretValue);
                }

                $projectId = getenv('GCP_PROJECT_ID');
                $secretClient = new SecretManagerServiceClient();
                $secretFullName = 'projects/' . $projectId . '/secrets/' . $secretName;

                try {
                    $secret = $secretClient->createSecret(
                        SecretManagerServiceClient::projectName($projectId),
                        $secretName,
                        new Secret([
                            'replication' => new Replication([
                                'automatic' => new Replication\Automatic()
                            ])
                        ])
                    );

                    $secretClient->addSecretVersion($secret->getName(), new SecretPayload(['data' => $secretValue]));
                } catch (Exception) {
                    $currentValue = $secretClient->accessSecretVersion($secretFullName . '/versions/latest')->getPayload()->getData();

                    if($currentValue != $secretValue) {
                        $secret = $secretClient->getSecret($secretFullName);
                        $secretClient->destroySecretVersion($secretFullName . '/versions/*');
                        $secretClient->addSecretVersion($secret->getName(), new SecretPayload(['data' => $secretValue]));
                    }
                }
            } catch (Exception $e) {
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
}
