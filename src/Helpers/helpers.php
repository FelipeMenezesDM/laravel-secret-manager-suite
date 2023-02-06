<?php

if(!function_exists('suite')) {
    function suite() : \FelipeMenezesDM\LaravelSecretManagerSuite\Suites\Suite
    {
        return \FelipeMenezesDM\LaravelSecretManagerSuite\Enums\DefaultAppSuitesEnum::tryFrom(getenv('APP_SUITE'))->getSuite();
    }
}

if(!function_exists('fromSecret')) {
    function fromSecret(string $secretName) : string|null
    {
        return suite()->getSecretData($secretName);
    }
}
