<?php

declare(strict_types=1);

namespace PamellaYamada\OTPHP;

use InvalidArgumentException;
use PamellaYamada\OTPHP\Enums\OTPLanguage;
use PamellaYamada\OTPHP\Enums\OTPProvider;
use PamellaYamada\OTPHP\I18n\Translator;
use PamellaYamada\OTPHP\QRCode\SVGRenderer;

/**
 * OTPHP
 *
 * High-performance, zero-dependency, internationalized OTP (TOTP/HOTP) authentication engine.
 *
 * @author Pamella Yamada de Araujo <YamadaPamella@gmail.com>
 * @license MIT
 *
 * @link https://github.com/PamellaYamada/otphp
 */
final class OTPHP
{
    private const BASE32_TABLE = [
        'A' => 0,  'B' => 1,  'C' => 2,  'D' => 3,  'E' => 4,  'F' => 5,  'G' => 6,  'H' => 7,
        'I' => 8,  'J' => 9,  'K' => 10, 'L' => 11, 'M' => 12, 'N' => 13, 'O' => 14, 'P' => 15,
        'Q' => 16, 'R' => 17, 'S' => 18, 'T' => 19, 'U' => 20, 'V' => 21, 'W' => 22, 'X' => 23,
        'Y' => 24, 'Z' => 25, '2' => 26, '3' => 27, '4' => 28, '5' => 29, '6' => 30, '7' => 31,
    ];

    private const BASE32_ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    /**
     * Set the global locale for framework exception messages and responses.
     */
    public static function setLocale(OTPLanguage $locale): void
    {
        Translator::setLocale($locale);
    }

    /**
     * Generate an OTP code based on time (TOTP) or counter (HOTP).
     *
     * @param  string  $secret  Key in Base32 format.
     * @param  OTPProvider  $provider  Target provider configuration.
     * @param  int|null  $timestamp  Optional Unix timestamp for testing.
     * @param  int  $counter  Event counter (required for HOTP mode).
     * @return string Calculated OTP code.
     */
    public static function generate(
        string $secret,
        OTPProvider $provider = OTPProvider::GOOGLE,
        ?int $timestamp = null,
        int $counter = 0
    ): string {
        [$algorithm, $digits, $period, $customAlphabet] = $provider->getConfig();

        $binarySecret = self::decodeBase32($secret);

        $mode = $provider->getMode();
        $factor = match ($mode) {
            'totp' => (int) floor(($timestamp ?? time()) / $period),
            'hotp' => $counter,
            default => throw new InvalidArgumentException("Unsupported mode: {$mode}"),
        };

        $packedCounter = pack('J', $factor);
        $hmacHash = hash_hmac($algorithm->value, $packedCounter, $binarySecret, true);

        $hashLength = strlen($hmacHash);
        $offset = ord($hmacHash[$hashLength - 1]) & 0x0F;

        $truncatedCode = (
            ((ord($hmacHash[$offset]) & 0x7F) << 24) |
            ((ord($hmacHash[$offset + 1]) & 0xFF) << 16) |
            ((ord($hmacHash[$offset + 2]) & 0xFF) << 8) |
            (ord($hmacHash[$offset + 3]) & 0xFF)
        );

        if ($customAlphabet !== null) {
            $alphabetLength = strlen($customAlphabet);
            $customOutput = '';
            for ($i = 0; $i < $digits; $i++) {
                $customOutput .= $customAlphabet[$truncatedCode % $alphabetLength];
                $truncatedCode = (int) ($truncatedCode / $alphabetLength);
            }

            return $customOutput;
        }

        $calculatedCode = $truncatedCode % (10 ** $digits);

        return str_pad((string) $calculatedCode, $digits, '0', STR_PAD_LEFT);
    }

    /**
     * Verify if a user-provided code is valid within the time window.
     */
    public static function verify(
        string $userCode,
        string $secret,
        OTPProvider $provider = OTPProvider::GOOGLE,
        int $window = 1,
        ?int $timestamp = null
    ): bool {
        $sanitizedUserCode = preg_replace('/[^a-zA-Z0-9]/', '', $userCode) ?? '';
        $cleanCode = strtoupper($sanitizedUserCode);
        [, $digits, $period] = $provider->getConfig();

        if (strlen($cleanCode) !== $digits) {
            return false;
        }

        $currentTime = $timestamp ?? time();

        for ($i = -$window; $i <= $window; $i++) {
            $testedTime = $currentTime + ($i * $period);
            $generatedCode = self::generate($secret, $provider, $testedTime);

            if (hash_equals($generatedCode, $cleanCode)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Create a new cryptographically secure Base32 secret key.
     */
    public static function createSecret(int $length = 32): string
    {
        if ($length < 1) {
            throw new \ValueError('Length must be greater than 0.');
        }

        /** @var int<1, max> $byteCount */
        $byteCount = (int) ceil($length * 5 / 8);
        $bytes = random_bytes($byteCount);
        $byteLength = strlen($bytes);
        $buffer = 0;
        $bitsLeft = 0;
        $base32 = '';

        for ($i = 0; $i < $byteLength; $i++) {
            $buffer = ($buffer << 8) | ord($bytes[$i]);
            $bitsLeft += 8;

            while ($bitsLeft >= 5) {
                $bitsLeft -= 5;
                $index = ($buffer >> $bitsLeft) & 0x1F;
                $base32 .= self::BASE32_ALPHABET[$index];
            }
        }

        return substr($base32, 0, $length);
    }

    /**
     * Render native Vector SVG XML tag for QR Code rendering.
     */
    public static function renderQrCodeSvg(
        string $secret,
        string $accountName,
        string $issuer,
        OTPProvider $provider = OTPProvider::GOOGLE,
        int $sizePixels = 200
    ): string {
        [$algorithm, $digits, $period] = $provider->getConfig();
        $label = rawurlencode($issuer).':'.rawurlencode($accountName);

        $uri = sprintf(
            'otpauth://totp/%s?secret=%s&issuer=%s&algorithm=%s&digits=%d&period=%d',
            $label,
            self::sanitizeSecret($secret),
            rawurlencode($issuer),
            strtoupper($algorithm->value),
            $digits,
            $period
        );

        return SVGRenderer::render($uri, $sizePixels);
    }

    private static function decodeBase32(string $rawSecret): string
    {
        $cleanSecret = self::sanitizeSecret($rawSecret);
        $length = strlen($cleanSecret);
        $bufferBits = 0;
        $bitsLeft = 0;
        $binary = '';

        for ($i = 0; $i < $length; $i++) {
            $char = $cleanSecret[$i];
            if (! isset(self::BASE32_TABLE[$char])) {
                continue;
            }

            $bufferBits = ($bufferBits << 5) | self::BASE32_TABLE[$char];
            $bitsLeft += 5;

            if ($bitsLeft >= 8) {
                $bitsLeft -= 8;
                $binary .= chr(($bufferBits >> $bitsLeft) & 0xFF);
            }
        }

        return $binary;
    }

    private static function sanitizeSecret(string $secret): string
    {
        return strtoupper(str_replace([' ', '-', '_', '.', "\t", "\n", "\r", '='], '', $secret));
    }
}
