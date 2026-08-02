<?php

declare(strict_types=1);

namespace PamellaYamada\OTPHP;

use PamellaYamada\OTPHP\Cache\CacheInterface;
use PamellaYamada\OTPHP\Cache\MemoryCache;
use PamellaYamada\OTPHP\Enums\OTPLanguage;
use PamellaYamada\OTPHP\Enums\OTPProvider;
use PamellaYamada\OTPHP\Exceptions\ExpiredCodeException;
use PamellaYamada\OTPHP\Exceptions\InvalidCodeException;
use PamellaYamada\OTPHP\Exceptions\InvalidSecretException;
use PamellaYamada\OTPHP\I18n\Translator;
use PamellaYamada\OTPHP\QRCode\SVGRenderer;
use PamellaYamada\OTPHP\Security\SecurityUtils;
use SensitiveParameter;
use ValueError;

final class OTPHP
{
    private static ?CacheInterface $cache = null;

    public static function setCache(CacheInterface $cache): void
    {
        self::$cache = $cache;
    }

    public static function setLocale(OTPLanguage $locale): void
    {
        Translator::setLocale($locale);
    }

    public static function createSecret(int $length = 32): string
    {
        if ($length <= 0) {
            throw new ValueError('O comprimento do segredo deve ser maior que zero.');
        }

        if ($length < 16) {
            $length = 16;
        }

        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = '';
        $max = strlen($alphabet) - 1;

        for ($i = 0; $i < $length; $i++) {
            $secret .= $alphabet[random_int(0, $max)];
        }

        return $secret;
    }

    public static function formatSecretForHuman(string $secret): string
    {
        $clean = strtoupper(trim(str_replace(' ', '', $secret)));

        return implode(' ', str_split($clean, 4));
    }

    public static function generate(
        #[SensitiveParameter] string $secret,
        OTPProvider $provider = OTPProvider::GOOGLE,
        ?int $timestamp = null
    ): string {
        $cleanSecret = strtoupper(trim(str_replace(' ', '', $secret)));

        if (! SecurityUtils::assertEntropy($cleanSecret, 128) || ! preg_match('/^[A-Z2-7]+=*$/', $cleanSecret)) {
            throw InvalidSecretException::invalidBase32();
        }

        [$algorithm, $digits, $period, $customAlphabet] = $provider->getConfig();
        $timeStep = (int) floor(($timestamp ?? time()) / $period);

        $binarySecret = self::base32Decode($cleanSecret);
        $binaryTime = pack('N*', 0).pack('N*', $timeStep);

        $hash = hash_hmac($algorithm->value, $binaryTime, $binarySecret, true);

        SecurityUtils::wipe($binarySecret);

        $offset = ord($hash[strlen($hash) - 1]) & 0x0F;

        $truncatedHash = (
            ((ord($hash[$offset]) & 0x7F) << 24) |
            ((ord($hash[$offset + 1]) & 0xFF) << 16) |
            ((ord($hash[$offset + 2]) & 0xFF) << 8) |
            (ord($hash[$offset + 3]) & 0xFF)
        );

        if ($customAlphabet !== null) {
            $code = '';
            $base = strlen($customAlphabet);
            for ($i = 0; $i < $digits; $i++) {
                $code = $customAlphabet[$truncatedHash % $base].$code;
                $truncatedHash = (int) ($truncatedHash / $base);
            }

            return $code;
        }

        $otp = $truncatedHash % (10 ** $digits);

        return str_pad((string) $otp, $digits, '0', STR_PAD_LEFT);
    }

    public static function verify(
        #[SensitiveParameter] string $code,
        #[SensitiveParameter] string $secret,
        OTPProvider $provider = OTPProvider::GOOGLE,
        int $window = 1,
        ?int $timestamp = null
    ): bool {
        $cleanCode = strtoupper(trim($code));
        [$algorithm, $digits, $period] = $provider->getConfig();

        if (strlen($cleanCode) !== $digits) {
            return false;
        }

        $currentTime = $timestamp ?? time();
        $validAccumulator = 0;

        for ($i = -$window; $i <= $window; $i++) {
            $targetTime = $currentTime + ($i * $period);
            $generatedToken = self::generate($secret, $provider, $targetTime);

            $validAccumulator |= SecurityUtils::constantTimeEquals($generatedToken, $cleanCode) ? 1 : 0;
        }

        return $validAccumulator === 1;
    }

    public static function verifyOrFail(
        #[SensitiveParameter] string $code,
        #[SensitiveParameter] string $secret,
        OTPProvider $provider = OTPProvider::GOOGLE,
        ?string $userId = null,
        int $window = 1,
        ?int $timestamp = null
    ): bool {
        $cleanCode = strtoupper(trim($code));
        [$algorithm, $digits, $period] = $provider->getConfig();

        if (strlen($cleanCode) !== $digits) {
            throw InvalidCodeException::invalidLength($digits, strlen($cleanCode), $provider->name);
        }

        $cache = self::$cache ?? new MemoryCache;
        $cacheKey = 'otphp_used_'.hash('sha256', ($userId ?? '').$secret.$cleanCode);

        if ($cache->has($cacheKey)) {
            throw InvalidCodeException::replayDetected();
        }

        $isValid = self::verify($cleanCode, $secret, $provider, $window, $timestamp);

        if (! $isValid) {
            throw ExpiredCodeException::expired();
        }

        $cache->set($cacheKey, true, $period * ($window + 1));

        return true;
    }

    public static function renderQrCodeSvg(
        #[SensitiveParameter] string $secret,
        string $holder,
        string $issuer,
        OTPProvider $provider = OTPProvider::GOOGLE,
        int $size = 200
    ): string {
        $otpauthUrl = sprintf(
            'otpauth://totp/%s:%s?secret=%s&issuer=%s',
            rawurlencode($issuer),
            rawurlencode($holder),
            $secret,
            rawurlencode($issuer)
        );

        return SVGRenderer::render($otpauthUrl, $size);
    }

    private static function base32Decode(#[SensitiveParameter] string $secret): string
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = strtoupper($secret);
        $paddingCharCount = substr_count($secret, '=');
        $allowedPaddingCount = [6, 4, 3, 1, 0];

        if (! in_array($paddingCharCount, $allowedPaddingCount, true)) {
            throw InvalidSecretException::invalidBase32();
        }

        $secret = str_replace('=', '', $secret);
        $binaryString = '';

        for ($i = 0; $i < strlen($secret); $i++) {
            $position = strpos($alphabet, $secret[$i]);
            if ($position === false) {
                throw InvalidSecretException::invalidBase32();
            }
            $binaryString .= str_pad(decbin($position), 5, '0', STR_PAD_LEFT);
        }

        $eightBitBytes = str_split($binaryString, 8);
        $decoded = '';

        foreach ($eightBitBytes as $byte) {
            if (strlen($byte) === 8) {
                $decoded .= chr(((int) bindec($byte)) & 0xFF);
            }
        }

        return $decoded;
    }
}
