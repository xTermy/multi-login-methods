<?php

namespace StormCode\MultiLoginMethods\Traits;

use StormCode\MultiLoginMethods\LoginMethodInterface;
use App\Exceptions\System\WrongLoginMethodException;
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

    public function chooseLoginMethod(string $methodName): LoginMethodInterface
    {
        $allowedMethods = $this->getAllowedLoginMethods();
        foreach ($allowedMethods as $method) {
            if ($method::getLoginMethodId() === $methodName) {
                return $method::markAsChosen($this);
            }
        }
    }
}
