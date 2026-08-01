<?php

declare(strict_types=1);

namespace PamellaYamada\OTPHP\Tests;

use PamellaYamada\OTPHP\Enums\OTPLanguage;
use PamellaYamada\OTPHP\Enums\OTPProvider;
use PamellaYamada\OTPHP\OTPHP;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ValueError;

final class OTPHPTestSuiteTest extends TestCase
{
    /*
    |--------------------------------------------------------------------------
    | 1. PROVEDORES E TAMANHO DE DÍGITOS
    |--------------------------------------------------------------------------
    */

    #[TestDox('Gera e valida tokens com o número exato de dígitos de cada provedor')]
    #[DataProvider('providersDigitsProvider')]
    public function test_generates_and_validates_tokens_for_providers(OTPProvider $provider, int $expectedDigits): void
    {
        $secret = OTPHP::createSecret(32);
        $code = OTPHP::generate($secret, $provider);

        $this->assertIsString($code);
        $this->assertSame($expectedDigits, strlen($code));

        if ($provider === OTPProvider::STEAM) {
            $this->assertMatchesRegularExpression('/^[23456789BCDFGHJKMNPQRTVWXY]{5}$/', $code);
        } else {
            $this->assertMatchesRegularExpression('/^\d{'.$expectedDigits.'}$/', $code);
        }

        $isValid = OTPHP::verify($code, $secret, $provider);
        $this->assertTrue($isValid);
    }

    public static function providersDigitsProvider(): array
    {
        return [
            'Google Authenticator (6 dígitos)' => [OTPProvider::GOOGLE, 6],
            'Microsoft (6 dígitos)' => [OTPProvider::MICROSOFT, 6],
            'Bitwarden (6 dígitos)' => [OTPProvider::BITWARDEN, 6],
            'Steam Guard (5 caracteres)' => [OTPProvider::STEAM, 5],
            'YubiKey (8 dígitos)' => [OTPProvider::YUBIKEY, 8],
            'Aegis (8 dígitos)' => [OTPProvider::AEGIS, 8],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | 2. GERAÇÃO DE SEGREDO BASE32
    |--------------------------------------------------------------------------
    */

    #[TestDox('Gera segredos Base32 válidos (RFC 4648)')]
    #[DataProvider('validSecretLengthsProvider')]
    public function test_generates_valid_base32_secrets(int $length): void
    {
        $secret = OTPHP::createSecret($length);

        $this->assertIsString($secret);
        $this->assertSame($length, strlen($secret));
        $this->assertMatchesRegularExpression('/^[A-Z2-7]+$/', $secret);
    }

    public static function validSecretLengthsProvider(): array
    {
        return [
            '16 Bytes' => [16],
            '32 Bytes' => [32],
            '64 Bytes' => [64],
        ];
    }

    #[TestDox('Lança ValueError do PHP ao solicitar tamanhos de bytes <= 0')]
    #[DataProvider('invalidSecretLengthsProvider')]
    public function test_throws_exception_for_invalid_secret_lengths(int $invalidLength): void
    {
        $this->expectException(ValueError::class);
        OTPHP::createSecret($invalidLength);
    }

    public static function invalidSecretLengthsProvider(): array
    {
        return [
            'Comprimento zero' => [0],
            'Comprimento negativo' => [-1],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | 3. QR CODE VETORIAL SVG (renderQrCodeSvg)
    |--------------------------------------------------------------------------
    */

    #[TestDox('Renderiza vetor SVG válido via renderQrCodeSvg')]
    public function test_renders_valid_vector_svg_qr_code(): void
    {
        $secret = OTPHP::createSecret(32);

        $svg = OTPHP::renderQrCodeSvg('user@enterprise.com', $secret, 'CorporateAuth', OTPProvider::GOOGLE);

        $this->assertIsString($svg);
        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('</svg>', $svg);
    }

    /*
    |--------------------------------------------------------------------------
    | 4. DRIFT & JANELA TEMPORAL
    |--------------------------------------------------------------------------
    */

    #[TestDox('Valida tolerância de desvio do relógio (Drift) com a janela temporizada')]
    public function test_clock_drift_window_tolerance(): void
    {
        $secret = OTPHP::createSecret(32);
        $currentTime = time();

        $pastCode = OTPHP::generate($secret, OTPProvider::GOOGLE, timestamp: $currentTime - 30);
        $futureCode = OTPHP::generate($secret, OTPProvider::GOOGLE, timestamp: $currentTime + 30);

        // Window = 0: Rejeita passados e futuros
        $this->assertFalse(OTPHP::verify($pastCode, $secret, OTPProvider::GOOGLE, timestamp: $currentTime, window: 0));
        $this->assertFalse(OTPHP::verify($futureCode, $secret, OTPProvider::GOOGLE, timestamp: $currentTime, window: 0));

        // Window = 1: Aceita variação de +/- 30 segundos
        $this->assertTrue(OTPHP::verify($pastCode, $secret, OTPProvider::GOOGLE, timestamp: $currentTime, window: 1));
        $this->assertTrue(OTPHP::verify($futureCode, $secret, OTPProvider::GOOGLE, timestamp: $currentTime, window: 1));
    }

    /*
    |--------------------------------------------------------------------------
    | 5. INTERNACIONALIZAÇÃO (setLocale)
    |--------------------------------------------------------------------------
    */

    #[TestDox('Altera o idioma sem erros através de todos os cases registrados no Enum OTPLanguage')]
    public function test_dynamic_i18n_language_switching(): void
    {
        $cases = OTPLanguage::cases();

        $this->assertNotEmpty($cases, 'Enum OTPLanguage não possui cases definidos.');

        foreach ($cases as $language) {
            OTPHP::setLocale($language);
            $this->assertTrue(true);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 6. EDGE CASES & ENTRADAS INVÁLIDAS
    |--------------------------------------------------------------------------
    */

    #[TestDox('Retorna falso para códigos contendo caracteres alfabéticos em provedores numéricos')]
    public function test_returns_false_for_alpha_code_on_numeric_provider(): void
    {
        $secret = OTPHP::createSecret(32);
        $this->assertFalse(OTPHP::verify('ABCDEF', $secret, OTPProvider::GOOGLE));
    }

    #[TestDox('Retorna falso para entradas de código totalmente vazias')]
    public function test_returns_false_for_empty_code_string(): void
    {
        $secret = OTPHP::createSecret(32);
        $this->assertFalse(OTPHP::verify('', $secret, OTPProvider::GOOGLE));
    }
}
