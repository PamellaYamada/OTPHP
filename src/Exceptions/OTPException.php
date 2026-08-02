<?php

declare(strict_types=1);

namespace PamellaYamada\OTPHP\Exceptions;

use Exception;
use PamellaYamada\OTPHP\I18n\Translator;

abstract class OTPException extends Exception
{
    public function __construct(
        string $translationKey,
        array $placeholders = [],
        int $code = 400
    ) {
        $translatedMessage = Translator::trans($translationKey, $placeholders);
        parent::__construct($translatedMessage, $code);
    }

    public function __debugInfo(): array
    {
        return [
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
        ];
    }
}
