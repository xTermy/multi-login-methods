<?php

namespace StormCode\MultiLoginMethods\Traits;

use Illuminate\Support\Facades\Config;

trait LoginMethodsTools
{
    private static function checkUserArgumentClass(object $user): bool
    {
        $userClass = Config::string('multiLoginMethods.auth_model');

        if (!($user instanceof $userClass)) {
            throw new \InvalidArgumentException("Invalid user class. Expected instance of {$userClass}.");
            return false;
        }

        return true;
    }
}
