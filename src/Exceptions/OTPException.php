<?php

declare(strict_types=1);

namespace PamellaYamada\OTPHP\Exceptions;

use Exception;
use PamellaYamada\OTPHP\I18n\Translator;

abstract class OTPException extends Exception
{
    /**
     * @param  array<string, mixed>  $placeholders
     */
    public function __construct(string $translationKey, array $placeholders = [], int $code = 400)
    {
        parent::__construct(Translator::trans($translationKey, $placeholders), $code);
    }
}
