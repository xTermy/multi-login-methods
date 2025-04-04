<?php

use Illuminate\Support\Facades\Config;
use StormCode\MultiLoginMethods\AttemptService;
use StormCode\MultiLoginMethods\LoginMethods\EmailLogin;
use StormCode\MultiLoginMethods\LoginMethods\PasswordLogin;
use StormCode\MultiLoginMethods\LoginMethods\SMSLogin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\DataProvider;
use StormCode\MultiLoginMethods\Models\LoginAttempt;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MultipleLoginTest extends TestCase
{
    use RefreshDatabase, WithFaker;

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

    /**
     * @throws Exception
     */
    #[DataProvider('userAccountDataProvider')]
    public function test_make_login_method_choice(string $email, string|null $phone, string|null $password, array $availableMethodsOutput): void
    {
        $userClass = Config::string('multiLoginMethods.auth_model');

        $user = $userClass::factory()->create([
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
        ]);

        $ip = $this->faker->ipv4();
        foreach($availableMethodsOutput as $methodClass) {
            $token = $user->chooseLoginMethod($methodClass, $ip);

            $this->assertTrue(gettype($token) === 'string');
            $this->assertTrue(strlen($token) > 0);
            $this->assertIsArray(decrypt($token));
        }
    }

    /**
     * @throws Exception
     */
    #[DataProvider('userAccountDataProvider')]
    public function test_make_login_attempt(string $email, string|null $phone, string|null $password, array $availableMethodsOutput): void
    {
        $userClass = Config::string('multiLoginMethods.auth_model');

        $user = $userClass::factory()->create([
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
        ]);

        foreach($availableMethodsOutput as $methodClass) {
            $ip = $this->faker->ipv4();
            $token = $user->chooseLoginMethod($methodClass, $ip);

            $loginAttempt = LoginAttempt::fromToken($token);

            if ($methodClass === PasswordLogin::class) {
                $wrongPasscode = $password.'123456';
                $passcode = $password;
            } else {
                $passcode = $loginAttempt->code;
                do {
                    $wrongPasscode = $this->faker->randomNumber(6);
                } while ($passcode === $wrongPasscode);
            }

            $loginAttempt = $loginAttempt->fresh();
            $this->assertTrue($loginAttempt->tries === 0);

            $attemptService = new AttemptService($token);
            $this->assertFalse($attemptService->checkLoginAttempt($ip, $wrongPasscode));

            $loginAttempt = $loginAttempt->fresh();
            $this->assertTrue($loginAttempt->tries === 1);

            $attemptService = new AttemptService($token);
            $this->assertTrue($attemptService->checkLoginAttempt($ip, $passcode));

            $loginAttempt = $loginAttempt->fresh();
            $this->assertTrue($loginAttempt->tries === 2);

            $this->assertThrows(fn() => new AttemptService($token), ModelNotFoundException::class);

            $loginAttempt = $loginAttempt->fresh();
            $this->assertTrue($loginAttempt->tries === 2);
        }
    }
}
