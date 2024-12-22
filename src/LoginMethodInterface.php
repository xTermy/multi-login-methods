<?php

namespace StormCode\MultiLoginMethods;

use StormCode\MultiLoginMethods\Models\LoginAttempt;

interface LoginMethodInterface
{
    public static function getLoginMethodId(): string;
    public static function checkAvailability(object $user): bool;
    public static function createNewAttempt(object $user, string $ip): string;
    public static function checkAttempt(LoginAttempt $loginAttempt, string $passcode): bool;
}
