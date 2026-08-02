<?php

declare(strict_types=1);

namespace PamellaYamada\OTPHP\Enums;

enum OTPLanguage: string
{
    case PT_BR = 'pt_BR';
    case PT_PT = 'pt_PT';
    case EN_US = 'en_US';
    case ES_ES = 'es_ES';
    case FR_FR = 'fr_FR';
    case DE_DE = 'de_DE';
    case IT_IT = 'it_IT';
    case NL_NL = 'nl_NL';
    case RU_RU = 'ru_RU';
    case UK_UA = 'uk_UA';
    case PL_PL = 'pl_PL';
    case CS_CZ = 'cs_CZ';
    case EL_GR = 'el_GR';
    case JA_JP = 'ja_JP';
    case ZH_CN = 'zh_CN';
    case ZH_TW = 'zh_TW';
    case KO_KR = 'ko_KR';
    case VI_VN = 'vi_VN';
    case TH_TH = 'th_TH';
    case ID_ID = 'id_ID';
    case HI_IN = 'hi_IN';
    case BN_BD = 'bn_BD';
    case AR_SA = 'ar_SA';
    case FA_IR = 'fa_IR';
    case HE_IL = 'he_IL';
    case TR_TR = 'tr_TR';
    case SW_KE = 'sw_KE';

    public function isRtl(): bool
    {
        return match ($this) {
            self::AR_SA, self::FA_IR, self::HE_IL => true,
            default => false,
        };
    }

    public function getNativeName(): string
    {
        return match ($this) {
            self::PT_BR => 'Português (Brasil)',
            self::PT_PT => 'Português (Portugal)',
            self::EN_US => 'English (US)',
            self::ES_ES => 'Español',
            self::FR_FR => 'Français',
            self::DE_DE => 'Deutsch',
            self::IT_IT => 'Italiano',
            self::NL_NL => 'Nederlands',
            self::RU_RU => 'Русский',
            self::UK_UA => 'Українська',
            self::PL_PL => 'Polski',
            self::CS_CZ => 'Čeština',
            self::EL_GR => 'Ελληνικά',
            self::JA_JP => '日本語',
            self::ZH_CN => '简体中文',
            self::ZH_TW => '繁體中文',
            self::KO_KR => '한국어',
            self::VI_VN => 'Tiếng Việt',
            self::TH_TH => 'ไทย',
            self::ID_ID => 'Bahasa Indonesia',
            self::HI_IN => 'हिन्दी',
            self::BN_BD => 'বাংলা',
            self::AR_SA => 'العربية',
            self::FA_IR => 'فارسی',
            self::HE_IL => 'עברית',
            self::TR_TR => 'Türkçe',
            self::SW_KE => 'Kiswahili',
        };
    }
}
