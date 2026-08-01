<?php

declare(strict_types=1);

namespace PamellaYamada\OTPHP\Exceptions;

final class InvalidCodeException extends OTPException
{
    public static function invalidLength(int $expected, int $actual): self
    {
        return new self('invalid_code_length', [
            'expected' => $expected,
            'actual' => $actual,
        ]);
    }

    public static function replayDetected(): self
    {
        return new self('replay_attack', [], 403);
    }
}
