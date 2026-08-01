<?php

declare(strict_types=1);

namespace OTPHP\Exceptions;

use Exception;
use OTPHP\I18n\Translator;

/**
 * Base Exception for all OTPHP framework errors.
 * Automatically translates error messages based on active locale.
 *
 * @author Pamella Yamada de Araujo <YamadaPamella@gmail.com>
 */
class OTPException extends Exception
{
    /**
     * @param string $translationKey Translation dictionary key
     * @param array<string, string|int> $placeholders Dynamic interpolation variables
     * @param int $code HTTP/Exception code
     */
    public function __construct(
        string $translationKey,
        array $placeholders = [],
        int $code = 400
    ) {
        $translatedMessage = Translator::trans($translationKey, $placeholders);
        parent::__construct($translatedMessage, $code);
    }
}
