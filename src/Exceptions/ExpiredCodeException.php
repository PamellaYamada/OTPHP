<?php

declare(strict_types=1);

namespace PamellaYamada\OTPHP\Exceptions;

final class ExpiredCodeException extends OTPException
{
    public static function expired(): self
    {
        return new self('code_expired', [], 401);
    }
}
