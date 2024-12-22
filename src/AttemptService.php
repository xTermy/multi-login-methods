<?php

namespace StormCode\MultiLoginMethods;

use Illuminate\Support\Facades\Config;
use StormCode\MultiLoginMethods\Exceptions\WrongLoginMethodException;
use StormCode\MultiLoginMethods\Models\LoginAttempt;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AttemptService
{
    private LoginAttempt $loginAttempt;

    public function __construct(string $token)
    {
        $this->loginAttempt = LoginAttempt::fromToken($token);
    }

    /**
     * @throws WrongLoginMethodException
     */
    public function checkLoginAttempt(string $attemptIp, string $code): bool
    {
        if (!isset($this->loginAttempt)) {
            throw new NotFoundHttpException('Login attempt not found');
        }

        $this->loginAttempt->update(['tries' => $this->loginAttempt->tries + 1]);
        $this->loginAttempt = $this->loginAttempt->fresh();

        if (
            $this->maxAttemptsExceeded()
            || !$this->confirmIpMatch($attemptIp)
        ) {
            return false;
        }

        if ($this->loginAttempt->method::checkAttempt($this->loginAttempt, $code)) {
            $this->loginAttempt->update(['succeed' => true]);
            return true;
        }

        return false;
    }


    private function maxAttemptsExceeded(): bool
    {
        if($this->loginAttempt->tries > Config::integer('multiLoginMethods.max_attempts', 3)) {
            throw new WrongLoginMethodException();
        } else {
            return false;
        }
    }

    private function confirmIpMatch(string $attemptIp): bool
    {
        return $attemptIp === $this->loginAttempt->ip;
    }
}
