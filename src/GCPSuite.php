<?php

namespace FelipeMenezesDM\LaravelSecretManagerSuite;

use FelipeMenezesDM\LaravelLoggerAdapter\LogPayload;
use FelipeMenezesDM\LaravelCommons\Enums\HttpStatusCode;
use Google\Cloud\SecretManager\V1\Replication;
use Google\Cloud\SecretManager\V1\Secret;
use Google\Cloud\SecretManager\V1\SecretManagerServiceClient;
use Exception;
use Google\Cloud\SecretManager\V1\SecretPayload;
use Illuminate\Support\Facades\Log;

class GCPSuite extends Suite
{
    protected static $isCloud = true;

    /** @Override */
    protected function getSecretData(string $secretName) : string
    {
        if(empty($secretName)) {
            return $secretName;
        }

        try {
            $secretClient = new SecretManagerServiceClient();
            $secret = $secretClient->accessSecretVersion('projects/' . getenv('GCP_PROJECT_ID') . '/secrets/' . $secretName . '/versions/latest');

            return $secret->getPayload()->getData();
        } catch (Exception $e) {
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
    public function createSecret(string $secretName, array|string $secretValue) : void
    {
        if(!empty($secretName)) {
            try {
                if (is_array($secretValue)) {
                    $secretValue = json_encode($secretValue);
                }

                $projectId = getenv('GCP_PROJECT_ID');
                $secretClient = new SecretManagerServiceClient();

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
                } catch (Exception) {
                    $secret = $secretClient->getSecret('projects/' . $projectId . '/secrets/' . $secretName);
                }

                $secretClient->addSecretVersion($secret->getName(), new SecretPayload(['data' => $secretValue]));
            } catch (Exception $e) {
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
}
