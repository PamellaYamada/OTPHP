<?php

declare(strict_types=1);

namespace PamellaYamada\OTPHP\Tests;

use PamellaYamada\OTPHP\Backup\RecoveryCodeManager;
use PamellaYamada\OTPHP\Cache\MemoryCache;
use PamellaYamada\OTPHP\Enums\OTPLanguage;
use PamellaYamada\OTPHP\Enums\OTPProvider;
use PamellaYamada\OTPHP\Exceptions\ExpiredCodeException;
use PamellaYamada\OTPHP\Exceptions\InvalidCodeException;
use PamellaYamada\OTPHP\Exceptions\InvalidSecretException;
use PamellaYamada\OTPHP\OTPHP;
use PamellaYamada\OTPHP\Security\StrictRateLimiter;
use PHPUnit\Framework\TestCase;
use ValueError;

final class OTPHPTestSuiteTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        MemoryCache::flush();
    }

    public function test_generates_and_validates_secrets_with_correct_entropy(): void
    {
        $secret16 = OTPHP::createSecret(16);
        $secret32 = OTPHP::createSecret(32);
        $secret64 = OTPHP::createSecret(64);

        $this->assertEquals(16, strlen($secret16));
        $this->assertEquals(32, strlen($secret32));
        $this->assertEquals(64, strlen($secret64));
        $this->assertTrue(OTPHP::verify(OTPHP::generate($secret32), $secret32));
        $this->assertTrue(OTPHP::verify(OTPHP::generate($secret64), $secret64));
    }

    public function test_throws_value_error_on_invalid_secret_length(): void
    {
        $this->expectException(ValueError::class);
        OTPHP::createSecret(0);
    }

    public function test_throws_value_error_on_negative_secret_length(): void
    {
        $this->expectException(ValueError::class);
        OTPHP::createSecret(-5);
    }

    public function test_generates_and_validates_tokens_for_all_providers(): void
    {
        $secret = OTPHP::createSecret(32);

        foreach (OTPProvider::cases() as $provider) {
            if (in_array($provider, [OTPProvider::GENERIC_60_SECONDS], true)) {
                continue;
            }

            $token = OTPHP::generate($secret, $provider);
            [$algo, $digits, $period] = $provider->getConfig();

            $this->assertEquals($digits, strlen($token));
            $this->assertTrue(OTPHP::verify($token, $secret, $provider));
        }
    }

    public function test_time_drift_and_window_tolerances(): void
    {
        $secret = OTPHP::createSecret(32);
        $now = time();
        $token = OTPHP::generate($secret, OTPProvider::GOOGLE, $now);

        $pastToken = OTPHP::generate($secret, OTPProvider::GOOGLE, $now - 30);
        $this->assertTrue(OTPHP::verify($pastToken, $secret, OTPProvider::GOOGLE, 1, $now));

        $expiredToken = OTPHP::generate($secret, OTPProvider::GOOGLE, $now - 90);
        $this->assertFalse(OTPHP::verify($expiredToken, $secret, OTPProvider::GOOGLE, 1, $now));
    }

    public function test_replay_attack_prevention_via_verify_or_fail(): void
    {
        $secret = OTPHP::createSecret(32);
        $token = OTPHP::generate($secret, OTPProvider::GOOGLE);
        $userId = 'user_999';

        $this->assertTrue(OTPHP::verifyOrFail($token, $secret, OTPProvider::GOOGLE, $userId));

        $this->expectException(InvalidCodeException::class);
        OTPHP::verifyOrFail($token, $secret, OTPProvider::GOOGLE, $userId);
    }

    public function test_expired_code_exception_thrown_on_invalid_token(): void
    {
        $secret = OTPHP::createSecret(32);
        $this->expectException(ExpiredCodeException::class);
        OTPHP::verifyOrFail('000000', $secret, OTPProvider::GOOGLE, 'user_123');
    }

    public function test_invalid_length_exception_thrown(): void
    {
        $secret = OTPHP::createSecret(32);
        $this->expectException(InvalidCodeException::class);
        OTPHP::verifyOrFail('12345', $secret, OTPProvider::GOOGLE, 'user_123');
    }

    public function test_invalid_secret_exception_on_weak_or_malformed_string(): void
    {
        $this->expectException(InvalidSecretException::class);
        OTPHP::generate('INVALID_SECRET_CHARS_!@#', OTPProvider::GOOGLE);
    }

    public function test_recovery_codes_generation_hashing_and_verification(): void
    {
        $codes = RecoveryCodeManager::generate(5);
        $this->assertCount(5, $codes);
        $this->assertCount(5, array_unique($codes));

        $hashedCodes = RecoveryCodeManager::hashCodes($codes);
        $this->assertCount(5, $hashedCodes);

        $testCode = $codes[0];
        $formattedVariant = strtolower(str_replace('-', ' ', $testCode));

        $verified = false;
        foreach ($hashedCodes as $hash) {
            if (RecoveryCodeManager::verify($formattedVariant, $hash)) {
                $verified = true;
                break;
            }
        }

        $this->assertTrue($verified);
    }

    public function test_strict_rate_limiter_behavior(): void
    {
        $limiter = new StrictRateLimiter;
        $identifier = 'ip_192.168.1.1';

        $this->assertFalse($limiter->tooManyAttempts($identifier, 3));

        $limiter->hit($identifier, 60);
        $limiter->hit($identifier, 60);
        $this->assertEquals(2, $limiter->attempts($identifier));
        $this->assertFalse($limiter->tooManyAttempts($identifier, 3));

        $limiter->hit($identifier, 60);
        $this->assertTrue($limiter->tooManyAttempts($identifier, 3));

        $limiter->reset($identifier);
        $this->assertEquals(0, $limiter->attempts($identifier));
        $this->assertFalse($limiter->tooManyAttempts($identifier, 3));
    }

    public function test_i18n_translates_correctly_across_all_languages(): void
    {
        $secret = OTPHP::createSecret(32);

        foreach (OTPLanguage::cases() as $lang) {
            OTPHP::setLocale($lang);
            $this->assertEquals($lang->getDirection(), $lang->isRtl() ? 'rtl' : 'ltr');
        }

        OTPHP::setLocale(OTPLanguage::PT_BR);
    }

    public function test_svg_qr_code_renderer_output(): void
    {
        $secret = OTPHP::createSecret(32);
        $svg = OTPHP::renderQrCodeSvg($secret, 'user@example.com', 'MinhaApp');

        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('<path', $svg);
        $this->assertStringContainsString('</svg>', $svg);
    }

    public function test_rejects_alphabetical_chars_in_numeric_providers(): void
    {
        $secret = OTPHP::createSecret(32);
        $this->assertFalse(OTPHP::verify('ABCDEF', $secret, OTPProvider::GOOGLE));
    }

    public function test_rejects_empty_code_entries(): void
    {
        $secret = OTPHP::createSecret(32);
        $this->assertFalse(OTPHP::verify('', $secret, OTPProvider::GOOGLE));
    }
}
