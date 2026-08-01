<?php

namespace PHPSTORM_META;

expectedArguments(
    \OTPHP\OTPHP::setLocale(),
    0,
    \OTPHP\Enums\OTPLanguage::PT_BR,
    \OTPHP\Enums\OTPLanguage::PT_PT,
    \OTPHP\Enums\OTPLanguage::EN_US,
    \OTPHP\Enums\OTPLanguage::ES_ES,
    \OTPHP\Enums\OTPLanguage::FR_FR,
    \OTPHP\Enums\OTPLanguage::DE_DE,
    \OTPHP\Enums\OTPLanguage::IT_IT,
    \OTPHP\Enums\OTPLanguage::NL_NL,
    \OTPHP\Enums\OTPLanguage::RU_RU,
    \OTPHP\Enums\OTPLanguage::UK_UA,
    \OTPHP\Enums\OTPLanguage::PL_PL,
    \OTPHP\Enums\OTPLanguage::CS_CZ,
    \OTPHP\Enums\OTPLanguage::EL_GR,
    \OTPHP\Enums\OTPLanguage::JA_JP,
    \OTPHP\Enums\OTPLanguage::ZH_CN,
    \OTPHP\Enums\OTPLanguage::ZH_TW,
    \OTPHP\Enums\OTPLanguage::KO_KR,
    \OTPHP\Enums\OTPLanguage::VI_VN,
    \OTPHP\Enums\OTPLanguage::TH_TH,
    \OTPHP\Enums\OTPLanguage::ID_ID,
    \OTPHP\Enums\OTPLanguage::HI_IN,
    \OTPHP\Enums\OTPLanguage::BN_BD,
    \OTPHP\Enums\OTPLanguage::AR_SA,
    \OTPHP\Enums\OTPLanguage::FA_IR,
    \OTPHP\Enums\OTPLanguage::HE_IL,
    \OTPHP\Enums\OTPLanguage::TR_TR,
    \OTPHP\Enums\OTPLanguage::SW_KE
);

expectedArguments(
    \OTPHP\OTPHP::generate(),
    1,
    \OTPHP\Enums\OTPProvider::GOOGLE,
    \OTPHP\Enums\OTPProvider::MICROSOFT,
    \OTPHP\Enums\OTPProvider::YUBIKEY,
    \OTPHP\Enums\OTPProvider::STEAM,
    \OTPHP\Enums\OTPProvider::AEGIS,
    \OTPHP\Enums\OTPProvider::BITWARDEN
);

expectedArguments(
    \OTPHP\OTPHP::verify(),
    2,
    \OTPHP\Enums\OTPProvider::GOOGLE,
    \OTPHP\Enums\OTPProvider::MICROSOFT,
    \OTPHP\Enums\OTPProvider::YUBIKEY,
    \OTPHP\Enums\OTPProvider::STEAM,
    \OTPHP\Enums\OTPProvider::AEGIS,
    \OTPHP\Enums\OTPProvider::BITWARDEN
);
