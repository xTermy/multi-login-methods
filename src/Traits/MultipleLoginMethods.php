<?php

namespace StormCode\MultiLoginMethods\Traits;

use StormCode\MultiLoginMethods\LoginMethodInterface;
use App\Exceptions\System\WrongLoginMethodException;
use Exception;
use Illuminate\Support\Facades\Config;

trait MultipleLoginMethods
{
    /**
     * @throws Exception
     */
    public function getAllowedLoginMethods(): array
    {
        $allowedMethods = [];
        foreach (Config::array('auth.enabled_login_methods') as $methodClass) {
            if (!in_array(LoginMethodInterface::class, class_implements($methodClass))) {
                throw new WrongLoginMethodException('Login method class ' . $methodClass . ' not implements LoginMethodInterface');
            }

            if ($methodClass::checkAvailability($this)) {
                $allowedMethods[] = $methodClass;
            }
        }

        return $allowedMethods;
    }

    public function
}
