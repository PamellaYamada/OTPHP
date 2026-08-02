<?php

declare(strict_types=1);

namespace PamellaYamada\OTPHP\Exceptions;

final class InvalidCodeException extends OTPException
{
    public static function invalidLength(int $expected, int $actual, string $provider = 'TOTP'): self
    {
        return new self('invalid_code_length', [
            'expected' => $expected,
            'actual' => $actual,
            'provider' => $provider,
        ]);
    }

    public static function replayDetected(): self
    {
        return new self('replay_attack', [], 403);
    }
}
