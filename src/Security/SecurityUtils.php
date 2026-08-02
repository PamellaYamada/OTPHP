<?php

declare(strict_types=1);

namespace PamellaYamada\OTPHP\Security;

use SensitiveParameter;

final class SecurityUtils
{
    public static function wipe(#[SensitiveParameter] string &$target): void
    {
        if (function_exists('sodium_memzero')) {
            sodium_memzero($target);
        } else {
            $length = strlen($target);
            for ($i = 0; $i < $length; $i++) {
                $target[$i] = "\0";
            }
            $target = '';
        }
    }

    public static function assertEntropy(#[SensitiveParameter] string $secret, int $minBits = 128): bool
    {
        $estimatedBits = strlen($secret) * 5;
        return $estimatedBits >= $minBits;
    }

    public static function constantTimeEquals(
        #[SensitiveParameter] string $knownString,
        #[SensitiveParameter] string $userString
    ): bool {
        return hash_equals($knownString, $userString);
    }
}
