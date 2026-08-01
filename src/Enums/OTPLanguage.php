<?php

declare(strict_types=1);

namespace OTPHP\Enums;

/**
 * Global languages supported by OTPHP Enterprise Engine.
 * Includes text direction metadata (LTR/RTL) for full UI compatibility.
 * 
 * @author Pamella Yamada de Araujo <YamadaPamella@gmail.com>
 */
enum OTPLanguage: string
{
    // Americas & Western Europe
    case PT_BR = 'pt_BR'; // Português (Brasil)
    case PT_PT = 'pt_PT'; // Português (Portugal)
    case EN_US = 'en_US'; // English (US)
    case ES_ES = 'es_ES'; // Español
    case FR_FR = 'fr_FR'; // Français
    case DE_DE = 'de_DE'; // Deutsch
    case IT_IT = 'it_IT'; // Italiano
    case NL_NL = 'nl_NL'; // Nederlands

    // Eastern Europe & Cyrillic
    case RU_RU = 'ru_RU'; // Русский (Russian)
    case UK_UA = 'uk_UA'; // Українська (Ukrainian)
    case PL_PL = 'pl_PL'; // Polski (Polish)
    case CS_CZ = 'cs_CZ'; // Čeština (Czech)
    case EL_GR = 'el_GR'; // Ελληνικά (Greek)

    // East & Southeast Asia
    case JA_JP = 'ja_JP'; // 日本語 (Japanese)
    case ZH_CN = 'zh_CN'; // 简体中文 (Simplified Chinese)
    case ZH_TW = 'zh_TW'; // 繁體中文 (Traditional Chinese)
    case KO_KR = 'ko_KR'; // 한국어 (Korean)
    case VI_VN = 'vi_VN'; // Tiếng Việt (Vietnamese)
    case TH_TH = 'th_TH'; // ไทย (Thai)
    case ID_ID = 'id_ID'; // Bahasa Indonesia (Indonesian)

    // South Asia & India
    case HI_IN = 'hi_IN'; // हिन्दी (Hindi)
    case BN_BD = 'bn_BD'; // বাংলা (Bengali)

    // Middle East & Africa (RTL Support)
    case AR_SA = 'ar_SA'; // العربية (Arabic - RTL)
    case FA_IR = 'fa_IR'; // فارسی (Persian - RTL)
    case HE_IL = 'he_IL'; // עברית (Hebrew - RTL)
    case TR_TR = 'tr_TR'; // Türkçe (Turkish)
    case SW_KE = 'sw_KE'; // Kiswahili (Swahili)

    /**
     * Returns true if the language is Right-to-Left (RTL).
     */
    public function isRtl(): bool
    {
        return match ($this) {
            self::AR_SA, self::FA_IR, self::HE_IL => true,
            default => false,
        };
    }
}
