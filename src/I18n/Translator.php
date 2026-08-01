<?php

declare(strict_types=1);

namespace PamellaYamada\OTPHP\I18n;

use PamellaYamada\OTPHP\Enums\OTPLanguage;

final class Translator
{
    private static OTPLanguage $locale = OTPLanguage::EN_US;

    /** @var array<string, array<string, string>> */
    private static array $dictionary = [
        'en_US' => [
            'invalid_code_length' => 'The provided code length is invalid for :provider. Expected :expected, got :actual.',
            'invalid_base32' => 'The secret key contains invalid Base32 characters.',
            'code_expired' => 'The authentication code has expired or is outside the valid time window.',
            'replay_attack' => 'Security Alert: This code has already been used.',
            'provider_mismatch' => 'Secret key format does not match rules for :provider.',
        ],
        'pt_BR' => [
            'invalid_code_length' => 'O tamanho do código é inválido para :provider. Esperado :expected, recebido :actual.',
            'invalid_base32' => 'A chave secreta contém caracteres Base32 inválidos.',
            'code_expired' => 'O código de autenticação expirou ou está fora da janela de tempo.',
            'replay_attack' => 'Alerta de Segurança: Este código já foi utilizado.',
            'provider_mismatch' => 'O formato da chave secreta não coincide com :provider.',
        ],
        'pt_PT' => [
            'invalid_code_length' => 'O comprimento do código é inválido para :provider. Esperado :expected, obtido :actual.',
            'invalid_base32' => 'A chave secreta contém carateres Base32 inválidos.',
            'code_expired' => 'O código de autenticação expirou.',
            'replay_attack' => 'Alerta de Segurança: Este código já foi utilizado.',
            'provider_mismatch' => 'O formato da chave secreta não coincide com :provider.',
        ],
        'es_ES' => [
            'invalid_code_length' => 'La longitud del código no es válida para :provider. Esperado :expected, obtenido :actual.',
            'invalid_base32' => 'La clave secreta contiene caracteres Base32 no válidos.',
            'code_expired' => 'El código de autenticación ha expirado.',
            'replay_attack' => 'Alerta de Seguridad: El código ya ha sido utilizado.',
            'provider_mismatch' => 'El formato de la clave no coincide con :provider.',
        ],
        'fr_FR' => [
            'invalid_code_length' => 'La longueur du code est invalide pour :provider. Attendu :expected, reçu :actual.',
            'invalid_base32' => 'La clé secrète contient des caractères Base32 invalides.',
            'code_expired' => 'Le code d\'authentification a expiré.',
            'replay_attack' => 'Alerte de Sécurité: Le code a déjà été utilisé.',
            'provider_mismatch' => 'Le format de la clé secrète ne correspond pas à :provider.',
        ],
        'de_DE' => [
            'invalid_code_length' => 'Die Codelänge ist für :provider ungültig. Erwartet: :expected, erhalten: :actual.',
            'invalid_base32' => 'Der Sicherheitsschlüssel enthält ungültige Base32-Zeichen.',
            'code_expired' => 'Der Authentifizierungscode ist abgelaufen.',
            'replay_attack' => 'Sicherheitswarnung: Code wurde bereits verwendet.',
            'provider_mismatch' => 'Das Schlüsselformat entspricht nicht :provider.',
        ],
        'it_IT' => [
            'invalid_code_length' => 'La lunghezza del codice non è valida per :provider. Atteso :expected, ricevuto :actual.',
            'invalid_base32' => 'La chiave segreta contiene caratteri Base32 non validi.',
            'code_expired' => 'Il codice di autenticazione è scaduto.',
            'replay_attack' => 'Avviso di sicurezza: il codice è già stato utilizzato.',
            'provider_mismatch' => 'Il formato della chiave non corrisponde a :provider.',
        ],
        'nl_NL' => [
            'invalid_code_length' => 'De codelengte is ongeldig voor :provider. Verwacht :expected, gekregen :actual.',
            'invalid_base32' => 'De geheimcode bevat ongeldige Base32-tekens.',
            'code_expired' => 'De authenticatiecode is verlopen.',
            'replay_attack' => 'Beveiligingswaarschuwing: Code is al gebruikt.',
            'provider_mismatch' => 'Sleutelformaat komt niet overeen met :provider.',
        ],
        'ru_RU' => [
            'invalid_code_length' => 'Неверная длина кода для :provider. Ожидалось :expected, получено :actual.',
            'invalid_base32' => 'Секретный ключ содержит недопустимые символы Base32.',
            'code_expired' => 'Срок действия кода аутентификации истек.',
            'replay_attack' => 'Предупреждение о безопасности: код уже использован.',
            'provider_mismatch' => 'Формат ключа не соответствует требованиям :provider.',
        ],
        'uk_UA' => [
            'invalid_code_length' => 'Недійсне значення довжини коду для :provider. Очікувалося :expected, отримано :actual.',
            'invalid_base32' => 'Секретний ключ містить неприпустимі символи Base32.',
            'code_expired' => 'Термін дії коду автентифікації закінчився.',
            'replay_attack' => 'Попередження безпеки: Код вже використано.',
            'provider_mismatch' => 'Формат ключа не відповідає :provider.',
        ],
        'pl_PL' => [
            'invalid_code_length' => 'Nieprawidłowa długość kodu dla :provider. Oczekiwano :expected, otrzymano :actual.',
            'invalid_base32' => 'Klucz prywatny zawiera nieprawidłowe znaki Base32.',
            'code_expired' => 'Kod uwierzytelniający wygasł.',
            'replay_attack' => 'Ostrzeżenie o bezpieczeństwie: Kod został już użyty.',
            'provider_mismatch' => 'Format klucza jest niezgodny z :provider.',
        ],
        'cs_CZ' => [
            'invalid_code_length' => 'Neplatná délka kódu pro :provider. Očekáváno :expected, obdrženo :actual.',
            'invalid_base32' => 'Tajný klíč obsahuje neplatné znaky Base32.',
            'code_expired' => 'Platnost ověřovacího kódu vypršela.',
            'replay_attack' => 'Bezpečnostní upozornění: Kód již byl použit.',
            'provider_mismatch' => 'Formát klíče neodpovídá :provider.',
        ],
        'el_GR' => [
            'invalid_code_length' => 'Μη έγκυρο μήκος κωδικού για :provider. Αναμενόταν :expected, λήφθηκε :actual.',
            'invalid_base32' => 'Το μυστικό κλειδί περιέχει μη έγκυρους χαρακτήρες Base32.',
            'code_expired' => 'Ο κωδικός επαλήθευσης έχει λήξει.',
            'replay_attack' => 'Ειδοποίηση ασφαλείας: Ο κωδικός έχει ήδη χρησιμοποιηθεί.',
            'provider_mismatch' => 'Η μορφή του κλειδιού δεν ταιριάζει με το :provider.',
        ],
        'ja_JP' => [
            'invalid_code_length' => ':provider のコード長が無効です。期待値: :expected、入力値: :actual。',
            'invalid_base32' => 'シークレットキーに無効なBase32文字が含まれています。',
            'code_expired' => '認証コードの有効期限が切れています。',
            'replay_attack' => 'セキュリティ警告: このコードは既に使用されています。',
            'provider_mismatch' => 'シークレットキーの形式が :provider のルールと一致しません。',
        ],
        'zh_CN' => [
            'invalid_code_length' => ':provider 的验证码长度无效。预期 :expected，实际 :actual。',
            'invalid_base32' => '密钥包含无效的 Base32 字符。',
            'code_expired' => '验证码已过期。',
            'replay_attack' => '安全警报：该验证码已被使用。',
            'provider_mismatch' => '密钥格式与 :provider 不符。',
        ],
        'zh_TW' => [
            'invalid_code_length' => ':provider 的驗證碼長度無效。預期 :expected，實際 :actual。',
            'invalid_base32' => '金鑰包含無效的 Base32 字元。',
            'code_expired' => '驗證碼已過期。',
            'replay_attack' => '安全警報：該驗證碼已被使用。',
            'provider_mismatch' => '金鑰格式與 :provider 不符。',
        ],
        'ko_KR' => [
            'invalid_code_length' => ':provider 의 코드 길이가 올바르지 않습니다. 예상: :expected, 입력: :actual.',
            'invalid_base32' => '비밀 키에 유효하지 않은 Base32 문자가 포함되어 있습니다.',
            'code_expired' => '인증 코드가 만료되었습니다.',
            'replay_attack' => '보안 경고: 이미 사용된 코드입니다.',
            'provider_mismatch' => '비밀 키 형식이 :provider 규칙과 일치하지 않습니다.',
        ],
        'vi_VN' => [
            'invalid_code_length' => 'Độ dài mã không hợp lệ cho :provider. Kỳ vọng :expected, nhận được :actual.',
            'invalid_base32' => 'Khóa bí mật chứa ký tự Base32 không hợp lệ.',
            'code_expired' => 'Mã xác thực đã hết hạn.',
            'replay_attack' => 'Cảnh báo bảo mật: Mã này đã được sử dụng.',
            'provider_mismatch' => 'Định dạng khóa không phù hợp với :provider.',
        ],
        'th_TH' => [
            'invalid_code_length' => 'ความยาวรหัสไม่ถูกต้องสำหรับ :provider คาดหวัง :expected ได้รับ :actual',
            'invalid_base32' => 'คีย์ลับมีอักขระ Base32 ที่ไม่ถูกต้อง',
            'code_expired' => 'รหัสยืนยันหมดอายุแล้ว',
            'replay_attack' => 'แจ้งเตือนความปลอดภัย: รหัสนี้ถูกใช้งานไปแล้ว',
            'provider_mismatch' => 'รูปแบบคีย์ลับไม่ตรงกับ :provider',
        ],
        'id_ID' => [
            'invalid_code_length' => 'Panjang kode tidak valid untuk :provider. Diharapkan :expected, diterima :actual.',
            'invalid_base32' => 'Kunci rahasia mengandung karakter Base32 yang tidak valid.',
            'code_expired' => 'Kode otentikasi telah kadaluwarsa.',
            'replay_attack' => 'Peringatan Keamanan: Kode ini sudah digunakan.',
            'provider_mismatch' => 'Format kunci tidak cocok dengan :provider.',
        ],
        'hi_IN' => [
            'invalid_code_length' => ':provider के लिए कोड की लंबाई अमान्य है। अपेक्षित :expected, प्राप्त :actual।',
            'invalid_base32' => 'सीक्रेट कुंजी में अमान्य Base32 वर्ण शामिल हैं।',
            'code_expired' => 'प्रमाणीकरण कोड की समयावधि समाप्त हो गई है।',
            'replay_attack' => 'सुरक्षा चेतावनी: यह कोड पहले ही उपयोग किया जा चुका है।',
            'provider_mismatch' => 'कुंजी प्रारूप :provider के नियमों से मेल नहीं खाता।',
        ],
        'bn_BD' => [
            'invalid_code_length' => ':provider এর জন্য কোডের দৈর্ঘ্য সঠিক নয়। প্রত্যাশিত :expected, পাওয়া গেছে :actual।',
            'invalid_base32' => 'গোপন কী-তে অবৈধ Base32 অক্ষর রয়েছে।',
            'code_expired' => 'প্রমাণীকরণ কোডের মেয়াদ শেষ হয়ে গেছে।',
            'replay_attack' => 'নিরাপত্তা সতর্কতা: এই কোডটি ইতিমধ্যে ব্যবহার করা হয়েছে।',
            'provider_mismatch' => 'কী বিন্যাস :provider এর সাথে মিলছে না।',
        ],
        'ar_SA' => [
            'invalid_code_length' => 'طول الرمز غير صالحة لـ :provider. المتوقع :expected، المستلم :actual.',
            'invalid_base32' => 'المفتاح السري يحتوي على أحرف Base32 غير صالحة.',
            'code_expired' => 'انتهت صلاحية رمز المصادقة.',
            'replay_attack' => 'تنبيه أمني: تم استخدام هذا الرمز من قبل.',
            'provider_mismatch' => 'تنسيق المفتاح لا يتوافق مع :provider.',
        ],
        'fa_IR' => [
            'invalid_code_length' => 'طول کد برای :provider نامعتبر است. مورد انتظار :expected، دریافت شده :actual.',
            'invalid_base32' => 'کلید امنیتی حاوی کاراکترهای Base32 نامعتبر است.',
            'code_expired' => 'کد تایید منقضی شده است.',
            'replay_attack' => 'هشدار امنیتی: این کد قبلاً استفاده شده است.',
            'provider_mismatch' => 'فرمت کلید با :provider مطابقت ندارد.',
        ],
        'he_IL' => [
            'invalid_code_length' => 'אורך הקוד אינו תקין עבור :provider. צפוי :expected, התקבל :actual.',
            'invalid_base32' => 'המפתח הסודי מכיל תווים שאינם תקינים ב-Base32.',
            'code_expired' => 'פג תוקפו של קוד האימות.',
            'replay_attack' => 'התראת אבטחה: קוד זה כבר היה בשימוש.',
            'provider_mismatch' => 'פורמט המפתח אינו תואם ל-:provider.',
        ],
        'tr_TR' => [
            'invalid_code_length' => ':provider için kod uzunluğu geçersiz. Beklenen :expected, alınan :actual.',
            'invalid_base32' => 'Gizli anahtar geçersiz Base32 karakterleri içeriyor.',
            'code_expired' => 'Doğrulama kodunun süresi doldu.',
            'replay_attack' => 'Güvenlik Uyarısı: Bu kod zaten kullanıldı.',
            'provider_mismatch' => 'Anahtar biçimi :provider kurallarıyla eşleşmiyor.',
        ],
        'sw_KE' => [
            'invalid_code_length' => 'Urefu wa msimbo si halali kwa :provider. Inatarajiwa :expected, imepokelewa :actual.',
            'invalid_base32' => 'Ufunguo wa siri una viboreshaji visivyo halali vya Base32.',
            'code_expired' => 'Msimbo wa uthibitisho umekwisha muda wake.',
            'replay_attack' => 'Tahadhari ya Usalama: Msimbo huu tayari umetumika.',
            'provider_mismatch' => 'Muundo wa ufunguo haulingani na :provider.',
        ],
    ];

    public static function setLocale(OTPLanguage $locale): void
    {
        self::$locale = $locale;
    }

    public static function getLocale(): OTPLanguage
    {
        return self::$locale;
    }

    /**
     * @param  array<string, string|int>  $replace
     */
    public static function trans(string $key, array $replace = []): string
    {
        $lang = self::$locale->value;
        $message = self::$dictionary[$lang][$key] ?? self::$dictionary['en_US'][$key] ?? $key;

        foreach ($replace as $placeholder => $value) {
            $message = str_replace(':'.$placeholder, (string) $value, $message);
        }

        return $message;
    }
}
