<?php

namespace FelipeMenezesDM\LaravelSecretManagerSuite\Suites;

class Suite
{
    protected static $isCloud = false;
    private static $instance;
    private static $dbUrl;
    private static $dbHost;
    private static $dbName;
    private static $dbUsername;
    private static $dbPassword;
    private static $mailHost;
    private static $mailUsername;
    private static $mailPassword;
    private static $reCaptchaSiteKey;
    private static $reCaptchaSecretKey;
    private static $adminEmail;
    private static $adminPassword;

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
     * Get mail server host from secret manager
     *
     * @return string
     */
    public function getMailHost() : string
    {
        if(self::$mailHost === null) {
            self::$mailHost = $this->getSecretData(getenv('MAIL_HOST'));
        }

        return self::$mailHost;
    }

    /**
     * Get mail server port from secret manager
     *
     * @return string
     */
    public function getMailPort() : int
    {
        return getenv('MAIL_PORT');
    }

    /**
     * Get mail server username from secret manager
     *
     * @return string
     */
    public function getMailUsername() : string
    {
        if(self::$mailUsername === null) {
            self::$mailUsername = $this->getSecretData(getenv('MAIL_USERNAME'));
        }

        return self::$mailUsername;
    }

    /**
     * Get mail server password from secret manager
     *
     * @return string
     */
    public function getMailPassword() : string
    {
        if(self::$mailPassword === null) {
            self::$mailPassword = $this->getSecretData(getenv('MAIL_PASSWORD'));
        }

        return self::$mailPassword;
    }

    /**
     * Get ReCaptcha site key from secret manager
     *
     * @return string
     */
    public function getReCaptchaSiteKey() : string
    {
        if(self::$reCaptchaSiteKey === null) {
            self::$reCaptchaSiteKey = $this->getSecretData(getenv('RECAPTCHA_SITE_KEY'));
        }

        return self::$reCaptchaSiteKey;
    }

    /**
     * Get ReCaptcha secret key from secret manager
     *
     * @return string
     */
    public function getReCaptchaSecretKey() : string
    {
        if(self::$reCaptchaSecretKey === null) {
            self::$reCaptchaSecretKey = $this->getSecretData(getenv('RECAPTCHA_SECRET_KEY'));
        }

        return self::$reCaptchaSecretKey;
    }

    /**
     * Get admin email from secret manager
     *
     * @return string
     */
    public function getAdminEmail() : string
    {
        if(self::$adminEmail === null) {
            self::$adminEmail = $this->getSecretData(getenv('ADMIN_EMAIL'));
        }

        return self::$adminEmail;
    }

    /**
     * Get admin password from secret manager
     *
     * @return string
     */
    public function getAdminPassword() : string
    {
        if(self::$adminPassword === null) {
            self::$adminPassword = $this->getSecretData(getenv('ADMIN_PASSWORD'));
        }

        return self::$adminPassword;
    }

    /**
     * Get server keys from secret manager
     *
     * @param string $secretName
     * @return string
     */
    public function getServerKeys(string $secretName) : string
    {
        return $this->getSecretData($secretName);
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
