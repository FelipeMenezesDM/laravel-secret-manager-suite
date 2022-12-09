<?php

namespace FelipeMenezesDM\LaravelSecretManagerSuite\Enums;

use FelipeMenezesDM\LaravelSecretManagerSuite\Suites\AWSSuite;
use FelipeMenezesDM\LaravelSecretManagerSuite\Suites\GCPSuite;
use FelipeMenezesDM\LaravelSecretManagerSuite\Suites\Suite;

enum DefaultAppSuitesEnum : string
{
    case GCP = 'gcp';
    case AWS = 'aws';
    case DEFAULT = '';

    /**
     * Get suite object singleton
     *
     * @return Suite
     */
    public function getSuite() : Suite
    {
        return match($this) {
            self::GCP => GCPSuite::getInstance(),
            self::AWS => AWSSuite::getInstance(),
            default => Suite::getInstance(),
        };
    }
}
