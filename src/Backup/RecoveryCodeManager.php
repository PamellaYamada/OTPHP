<?php

declare(strict_types=1);

namespace PamellaYamada\OTPHP\Backup;

use PamellaYamada\OTPHP\Security\SecurityUtils;
use SensitiveParameter;

final class RecoveryCodeManager
{
    private const ALPHABET = '23456789ABCDEFGHJKMNPQRSTUVWXYZ';

    /**
     * @return list<string>
     */
    public static function generate(int $amount = 8): array
    {
        $codes = [];
        $maxIndex = strlen(self::ALPHABET) - 1;

        while (count($codes) < $amount) {
            $code = '';
            for ($j = 0; $j < 10; $j++) {
                $code .= self::ALPHABET[random_int(0, $maxIndex)];
            }
            $formatted = substr($code, 0, 5).'-'.substr($code, 5, 5);

            if (! in_array($formatted, $codes, true)) {
                $codes[] = $formatted;
            }
        }

        return $codes;
    }

    /**
     * @param  array<int, string>  $codes
     * @return array<int, string>
     */
    public static function hashCodes(array $codes): array
    {
        $algo = defined('PASSWORD_ARGON2ID') ? \PASSWORD_ARGON2ID : \PASSWORD_DEFAULT;
        $options = defined('PASSWORD_ARGON2ID') ? [
            'memory_cost' => 65536,
            'time_cost' => 4,
            'threads' => 2,
        ] : [];

        return array_map(fn ($code) => password_hash($code, $algo, $options), $codes);
    }

    public static function verify(#[SensitiveParameter] string $inputCode, string $hashedCode): bool
    {
        $cleanInput = strtoupper(trim(str_replace([' ', '-'], '', $inputCode)));

        if (strlen($cleanInput) === 10) {
            $cleanInput = substr($cleanInput, 0, 5).'-'.substr($cleanInput, 5, 5);
        }

        try {
            return password_verify($cleanInput, $hashedCode);
        } finally {
            SecurityUtils::wipe($cleanInput);
        }
    }
}
