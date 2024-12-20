<?php

namespace StormCode\MultiLoginMethods;

interface LoginMethodInterface
{
    public static function getLoginMethodId(): string;
    public static function checkAvailability(object $user): bool;
    public static function createNewAttempt(object $user): string;
}
