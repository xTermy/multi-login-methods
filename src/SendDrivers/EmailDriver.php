<?php

namespace StormCode\MultiLoginMethods\SendDrivers;

use Illuminate\Support\Facades\Mail;
use StormCode\MultiLoginMethods\Mail\LoginCodeMail;
use StormCode\MultiLoginMethods\SendDriverInterface;

class EmailDriver implements SendDriverInterface
{
    public static function send(string $to, string $passcode): void
    {
        Mail::to($to)->send(new LoginCodeMail($passcode));
    }
}
