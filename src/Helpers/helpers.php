<?php

if(!function_exists('suite')) {
    function suite() : \FelipeMenezesDM\LaravelSecretManagerSuite\Suites\Suite
    {
        return \FelipeMenezesDM\LaravelSecretManagerSuite\Enums\DefaultAppSuitesEnum::tryFrom(getenv('APP_SUITE'))->getSuite();
    }
}
