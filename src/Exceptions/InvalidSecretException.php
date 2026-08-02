<?php

declare(strict_types=1);

namespace PamellaYamada\OTPHP\Exceptions;

final class InvalidSecretException extends OTPException
{
    public static function invalidBase32(): self
    {
        return new self('invalid_secret_base32', [], 422);
    }

    public static function providerMismatch(string $provider): self
    {
        return new self('provider_mismatch', ['provider' => $provider], 422);
    }
}
