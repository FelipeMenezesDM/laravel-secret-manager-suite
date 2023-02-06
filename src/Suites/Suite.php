<?php

namespace FelipeMenezesDM\LaravelSecretManagerSuite\Suites;

class Suite
{
    protected static $isCloud = false;
    private static $instance;

    /**
     * Singleton pattern
     *
     * @return Suite
     */
    public static function getInstance() : Suite
    {
        if(self::$instance === null) {
            self::$instance = new static;
        }

        return self::$instance;
    }

    /**
     * Check if suite objet is in a cloud environment
     *
     * @return bool
     */
    public function isCloud() : bool
    {
        return self::$isCloud;
    }

    /**
     * Get secret value
     *
     * @param string $secretName
     * @return string
     */
    public function getSecretData(string $secretName) : string
    {
        return $secretName;
    }

    /**
     * Create new secret
     *
     * @param string $secretName
     * @param array|string $secretValue
     */
    public function createSecret(string $secretName, array|string $secretValue) : void {}

    /**
     * Create a cache key
     *
     * @param string $key
     * @return string
     */
    protected function cacheKey(string $key): string
    {
        return sprintf('%s::%s', self::class, $key);
    }
}
