<?php

namespace StormCode\MultiLoginMethods\Tests;

use Illuminate\Support\Facades\Config;
use StormCode\MultiLoginMethods\LoginMethods\EmailLogin;
use StormCode\MultiLoginMethods\LoginMethods\PasswordLogin;
use StormCode\MultiLoginMethods\LoginMethods\SMSLogin;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public static function userAccountDataProvider(): array
    {
        return [
            'full_user' => ['user1@stormcode.pl', '+48509466782', 'password123', [EmailLogin::class, PasswordLogin::class, SMSLogin::class]],
            'user_without_pass' => ['user2@stormcode.pl', '+48511256698', null, [EmailLogin::class, SMSLogin::class]],
            'user_without_phone' => ['user3@stormcode.pl', null, 'password123', [EmailLogin::class, PasswordLogin::class]],
            'user_with_email_only' => ['user4@stormcode.pl', null, null, [EmailLogin::class]],
        ];
    }

    /**
     * @throws Exception
     */
    #[DataProvider('userAccountDataProvider')]
    public function test_getting_allowed_login_methods(string $email, string|null $phone, string|null $password, array $availableMethodsOutput): void
    {
        $userClass = Config::string('multiLoginMethods.auth_model');

        $user = $userClass::factory()->create([
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
        ]);

        $this->assertEquals($user->getAllowedLoginMethods(), $availableMethodsOutput);
    }
}
