<?php

declare(strict_types=1);

namespace OTPHP\Backup;

final class RecoveryCodeManager
{
    /**
     * @param int $amount Number of recovery codes to generate
     * @return array<int, string>
     */
    public static function generate(int $amount = 8): array
    {
        $codes = [];
        for ($i = 0; $i < $amount; $i++) {
            $bytes = random_bytes(5);
            $hash = strtoupper(bin2hex($bytes));
            $codes[] = substr($hash, 0, 5) . '-' . substr($hash, 5, 5);
        }
        return $codes;
    }

    /**
     * @param array<int, string> $codes
     * @return array<int, string> Hashed codes for database persistence
     */
    public static function hashCodes(array $codes): array
    {
        return array_map(fn($code) => password_hash($code, PASSWORD_ARGON2ID), $codes);
    }
}
