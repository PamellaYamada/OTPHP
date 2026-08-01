<?php

declare(strict_types=1);

namespace OTPHP\Enums;

enum OTPProvider
{
    case GOOGLE;
    case MICROSOFT;
    case YUBIKEY;
    case STEAM;
    case BITWARDEN;
    case AEGIS;

    /**
     * Get algorithm, digit count, time step, and custom alphabet settings.
     * 
     * @return array{0: OTPAlgorithm, 1: int, 2: int, 3: string|null}
     */
    public function getConfig(): array
    {
        return match ($this) {
            self::GOOGLE, self::BITWARDEN => [OTPAlgorithm::SHA1, 6, 30, null],
            self::MICROSOFT => [OTPAlgorithm::SHA1, 6, 30, null],
            self::YUBIKEY => [OTPAlgorithm::SHA256, 8, 30, null],
            self::STEAM => [OTPAlgorithm::SHA1, 5, 30, '23456789BCDFGHJKMNPQRTVWXY'],
            self::AEGIS => [OTPAlgorithm::SHA512, 8, 15, null],
        };
    }

    public function getMode(): string
    {
        return 'totp';
    }
}
