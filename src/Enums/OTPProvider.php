<?php

declare(strict_types=1);

namespace PamellaYamada\OTPHP\Enums;

enum OTPProvider: string
{
    case GOOGLE     = 'GOOGLE';
    case MICROSOFT  = 'MICROSOFT';
    case GITHUB     = 'GITHUB';
    case STEAM      = 'STEAM';

    /**
     * @return array{0: OTPAlgorithm, 1: int, 2: int, 3: ?string}
     */
    public function getConfig(): array
    {
        return match ($this) {
            self::GOOGLE, self::MICROSOFT, self::GITHUB => [OTPAlgorithm::SHA1, 6, 30, null],
            self::STEAM => [OTPAlgorithm::SHA1, 5, 30, '23456789BCDFGHJKMNPQRTVWXY'],
        };
    }
}
