<?php

namespace FelipeMenezesDM\LaravelSecretManagerSuite;

class Suite
{
    protected static $isCloud = false;
    private static $instance;
    private static $dbUrl;
    private static $dbHost;
    private static $dbName;
    private static $dbUsername;
    private static $dbPassword;

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
     * Get database URL from secret manager
     *
     * @return string
     */
    public function getDBUrl() : string
    {
        if(self::$dbUrl === null) {
            self::$dbUrl = $this->getSecretData(getenv('DB_URL'));
        }

        return self::$dbUrl;
    }

    /**
     * Get database host from secret manager
     *
     * @return string
     */
    public function getDBHost() : string
    {
        if(self::$dbHost === null) {
            self::$dbHost = $this->getSecretData(getenv('DB_HOST'));
        }

        return self::$dbHost;
    }

    /**
     * Return database host port from secret manager
     *
     * @return string
     */
    public function getDBPort() : string
    {
        return getenv('DB_PORT');
    }

    /**
     * Get database username from secret manager
     *
     * @return string
     */
    public function getDBUsername() : string
    {
        if(self::$dbUsername === null) {
            self::$dbUsername = $this->getSecretData(getenv('DB_USERNAME'));
        }

        return self::$dbUsername;
    }

    /**
     * Get database user password from secret nanager
     *
     * @return string
     */
    public function getDBPassword() : string
    {
        if(self::$dbPassword === null) {
            self::$dbPassword = $this->getSecretData(getenv('DB_PASSWORD'));
        }

        return self::$dbPassword;
    }

    /**
     * Get database name from secret manager
     *
     * @return string
     */
    public function getDBName() : string
    {
        if(self::$dbName === null) {
            self::$dbName = $this->getSecretData(getenv('DB_DATABASE'));
        }

        return self::$dbName;
    }

    /**
     * Get secret value
     *
     * @param string $secretName
     * @return string
     */
    protected function getSecretData(string $secretName) : string
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
}
