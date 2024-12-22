<?php

namespace StormCode\MultiLoginMethods\Traits;

use StormCode\MultiLoginMethods\LoginMethodInterface;
use StormCode\MultiLoginMethods\Exceptions\System\WrongLoginMethodException;
use Exception;
use Illuminate\Support\Facades\Config;

trait MultipleLoginMethods
{
    private function checkIfLoginMethodIsCorrect(string $methodClass): void
    {
        if (!in_array(LoginMethodInterface::class, class_implements($methodClass))) {
            throw new WrongLoginMethodException('Login method class ' . $methodClass . ' not implements LoginMethodInterface');
        }
    }

    private function checkIfLoginMethodIsAllowed(string $methodClass): void
    {
        if (!in_array($methodClass, $this->getAllowedLoginMethods())) {
            throw new WrongLoginMethodException('Login method class ' . $methodClass . ' is not allowed.');
        }
    }

    /**
     * @throws Exception
     */
    public function getAllowedLoginMethods(): array
    {
        $allowedMethods = [];
        foreach (Config::array('multiLoginMethods.enabled_login_methods') as $methodClass) {
            $this->checkIfLoginMethodIsCorrect($methodClass);

            if ($methodClass::checkAvailability($this)) {
                $allowedMethods[] = $methodClass;
            }
        }

        return $allowedMethods;
    }

    public function chooseLoginMethod(string $methodClass, string $ip): string
    {
        $this->checkIfLoginMethodIsAllowed($methodClass);

        $token = $methodClass::createNewAttempt($this, $ip);

        return $token;
    }
}
