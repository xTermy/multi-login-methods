<?php

namespace StormCode\MultiLoginMethods;

interface SendDriverInterface
{
    public static function send(string $to, string $passcode): void;
}
