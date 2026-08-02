<?php

declare(strict_types=1);

namespace PamellaYamada\OTPHP\I18n;

use PamellaYamada\OTPHP\Enums\OTPLanguage;

final class Translator
{
    private static OTPLanguage $locale = OTPLanguage::PT_BR;

    /**
     * Dicionário completo cobrindo todos os 27 idiomas suportados pelo Enum OTPLanguage.
     *
     * @var array<string, array<string, string>>
     */
    private static array $dictionary = [
        // 1. Português (Brasil)
        'pt_BR' => [
            'code_expired'          => 'O código informado expirou ou é inválido.',
            'invalid_code_length'   => 'O código deve conter exatamente :expected caracteres para o provedor :provider (fornecido: :actual).',
            'invalid_secret_base32' => 'A chave secreta não é uma string Base32 válida ou não possui entropia suficiente.',
            'replay_attack'         => 'Ataque de reutilização detectado. Este código já foi utilizado.',
            'provider_mismatch'     => 'A chave não é compatível com o provedor :provider.',
        ],
        // 2. Português (Portugal)
        'pt_PT' => [
            'code_expired'          => 'O código introduzido expirou ou é inválido.',
            'invalid_code_length'   => 'O código deve conter exatamente :expected carateres para o fornecedor :provider (fornecido: :actual).',
            'invalid_secret_base32' => 'A chave secreta não é uma string Base32 válida ou não possui entropia suficiente.',
            'replay_attack'         => 'Ataque de reutilização detetado. Este código já foi utilizado.',
            'provider_mismatch'     => 'A chave não é compatível com o fornecedor :provider.',
        ],
        // 3. Inglês (EUA)
        'en_US' => [
            'code_expired'          => 'The provided code has expired or is invalid.',
            'invalid_code_length'   => 'Code must be exactly :expected characters for provider :provider (given: :actual).',
            'invalid_secret_base32' => 'The secret key is not a valid Base32 string or lacks entropy.',
            'replay_attack'         => 'Replay attack detected. This code has already been used.',
            'provider_mismatch'     => 'Secret key mismatch for provider :provider.',
        ],
        // 4. Espanhol
        'es_ES' => [
            'code_expired'          => 'El código proporcionado ha expirado o no es válido.',
            'invalid_code_length'   => 'El código debe tener exactamente :expected caracteres para :provider (proporcionado: :actual).',
            'invalid_secret_base32' => 'La clave secreta no es una cadena Base32 válida o carece de entropía.',
            'replay_attack'         => 'Ataque de reutilización detectado. Este código ya ha sido utilizado.',
            'provider_mismatch'     => 'Incompatibilidad de clave para el proveedor :provider.',
        ],
        // 5. Francês
        'fr_FR' => [
            'code_expired'          => 'Le code fourni a expiré ou est invalide.',
            'invalid_code_length'   => 'Le code doit contenir exactement :expected caractères pour le fournisseur :provider (fourni: :actual).',
            'invalid_secret_base32' => 'La clé secrète n\'est pas une chaîne Base32 valide ou manque d\'entropie.',
            'replay_attack'         => 'Attaque par rejeu détectée. Ce code a déjà été utilisé.',
            'provider_mismatch'     => 'Incompatibilité de clé pour le fournisseur :provider.',
        ],
        // 6. Alemão
        'de_DE' => [
            'code_expired'          => 'Der eingegebene Code ist abgelaufen oder ungültig.',
            'invalid_code_length'   => 'Der Code muss genau :expected Zeichen für den Anbieter :provider lang sein (angegeben: :actual).',
            'invalid_secret_base32' => 'Der Geheimschlüssel ist kein gültiger Base32-String oder hat unzureichende Entropie.',
            'replay_attack'         => 'Replay-Angriff erkannt. Dieser Code wurde bereits verwendet.',
            'provider_mismatch'     => 'Schlüssel stimmt nicht mit dem Anbieter :provider überein.',
        ],
        // 7. Italiano
        'it_IT' => [
            'code_expired'          => 'Il codice fornito è scaduto o non è valido.',
            'invalid_code_length'   => 'Il codice deve contenere esattamente :expected caratteri per il provider :provider (fornito: :actual).',
            'invalid_secret_base32' => 'La chiave segreta non è una stringa Base32 valida o manca di entropia.',
            'replay_attack'         => 'Attacco di replay rilevato. Questo codice è già stato utilizzato.',
            'provider_mismatch'     => 'Incompatibilità della chiave segreta per il provider :provider.',
        ],
        // 8. Holandês
        'nl_NL' => [
            'code_expired'          => 'De opgegeven code is verlopen of ongeldig.',
            'invalid_code_length'   => 'De code moet precies :expected tekens lang zijn voor provider :provider (opgegeven: :actual).',
            'invalid_secret_base32' => 'De geheime sleutel is geen geldige Base32-tekenreeks of mist entropie.',
            'replay_attack'         => 'Replay-aanval gedetecteerd. Deze code is al gebruikt.',
            'provider_mismatch'     => 'Sleutel komt niet overeen met provider :provider.',
        ],
        // 9. Russo
        'ru_RU' => [
            'code_expired'          => 'Введенный код истек или недействителен.',
            'invalid_code_length'   => 'Код должен содержать ровно :expected символов для провайдера :provider (введено: :actual).',
            'invalid_secret_base32' => 'Секретный ключ не является валидной строкой Base32 или имеет низкую энтропию.',
            'replay_attack'         => 'Обнаружена повторная атака. Этот код уже использовался.',
            'provider_mismatch'     => 'Секретный ключ не подходит для провайдера :provider.',
        ],
        // 10. Ucraniano
        'uk_UA' => [
            'code_expired'          => 'Введений код вичерпав термін дії або є недійсним.',
            'invalid_code_length'   => 'Код повинен містити точно :expected символів для провайдера :provider (введено: :actual).',
            'invalid_secret_base32' => 'Секретний ключ не є валідною строкою Base32 або має недостатньо ентропії.',
            'replay_attack'         => 'Виявлено повторну атаку. Цей код вже використовувався.',
            'provider_mismatch'     => 'Секретний ключ не відповідає провайдеру :provider.',
        ],
        // 11. Polonês
        'pl_PL' => [
            'code_expired'          => 'Podany kod wygasł lub jest nieprawidłowy.',
            'invalid_code_length'   => 'Kod musi mieć dokładnie :expected znaków dla dostawcy :provider (podano: :actual).',
            'invalid_secret_base32' => 'Klucz prywatny nie jest prawidłowym ciągiem Base32 lub ma zbyt niską entropię.',
            'replay_attack'         => 'Wykryto atak typu replay. Ten kod został już użyty.',
            'provider_mismatch'     => 'Niezgodność klucza dla dostawcy :provider.',
        ],
        // 12. Checo
        'cs_CZ' => [
            'code_expired'          => 'Zadaný kód vypršel nebo je neplatný.',
            'invalid_code_length'   => 'Kód musí mít přesně :expected znaků pro poskytovatele :provider (zadáno: :actual).',
            'invalid_secret_base32' => 'Tajný klíč není platný řetězec Base32 nebo nemá dostatečnou entropii.',
            'replay_attack'         => 'Zjištěn opakovaný útok (replay attack). Tento kód již byl použit.',
            'provider_mismatch'     => 'Nesoulad tajného klíče pro poskytovatele :provider.',
        ],
        // 13. Grego
        'el_GR' => [
            'code_expired'          => 'Ο κωδικός που δόθηκε έχει λήξει ή είναι άκυρος.',
            'invalid_code_length'   => 'Ο κωδικός πρέπει να έχει ακριβώς :expected χαρακτήρες για τον πάροχο :provider (δόθηκαν: :actual).',
            'invalid_secret_base32' => 'Το μυστικό κλειδί δεν είναι έγκυρη συμβολοσειρά Base32 ή στερείται εντροπίας.',
            'replay_attack'         => 'Εντοπίστηκε επίθεση επανάληψης (replay attack). Αυτός ο κωδικός έχει ήδη χρησιμοποιηθεί.',
            'provider_mismatch'     => 'Ασυμβατότητα μυστικού κλειδιού για τον πάροχο :provider.',
        ],
        // 14. Japonês
        'ja_JP' => [
            'code_expired'          => '入力されたコードは期限切れか無効です。',
            'invalid_code_length'   => 'プロバイダー :provider のコードは正確に :expected 文字である必要があります (入力値: :actual)。',
            'invalid_secret_base32' => 'シークレットキーが有効な Base32 文字列でないか、エントロピーが不足しています。',
            'replay_attack'         => 'リプレイ攻撃が検知されました。このコードは既に使用されています。',
            'provider_mismatch'     => 'プロバイダー :provider のシークレットキーが一致しません。',
        ],
        // 15. Chinês Simplificado
        'zh_CN' => [
            'code_expired'          => '提供的验证码已过期或无效。',
            'invalid_code_length'   => '提供商 :provider 的验证码必须恰好为 :expected 位（输入为：:actual）。',
            'invalid_secret_base32' => '密钥不是有效的 Base32 字符串或熵不足。',
            'replay_attack'         => '检测到重放攻击。该验证码已被使用。',
            'provider_mismatch'     => '提供商 :provider 的密钥不匹配。',
        ],
        // 16. Chinês Tradicional
        'zh_TW' => [
            'code_expired'          => '提供的驗證碼已過期或無效。',
            'invalid_code_length'   => '提供者 :provider 的驗證碼必須恰好為 :expected 位（輸入為：:actual）。',
            'invalid_secret_base32' => '金鑰不是有效的 Base32 字串或熵不足。',
            'replay_attack'         => '檢測到重放攻擊。該驗證碼已被使用。',
            'provider_mismatch'     => '提供者 :provider 的金鑰不匹配。',
        ],
        // 17. Coreano
        'ko_KR' => [
            'code_expired'          => '제공된 코드가 만료되었거나 유효하지 않습니다.',
            'invalid_code_length'   => ':provider 서비스의 코드는 정확히 :expected 자리어야 합니다 (입력값: :actual).',
            'invalid_secret_base32' => '시크릿 키가 유효한 Base32 문자열이 아니거나 엔트로피가 부족합니다.',
            'replay_attack'         => '재전송 공격(Replay Attack)이 감지되었습니다. 이 코드는 이미 사용되었습니다.',
            'provider_mismatch'     => ':provider 서비스의 시크릿 키가 일치하지 않습니다.',
        ],
        // 18. Vietnamita
        'vi_VN' => [
            'code_expired'          => 'Mã đã cung cấp đã hết hạn hoặc không hợp lệ.',
            'invalid_code_length'   => 'Mã phải có đúng :expected ký tự cho nhà cung cấp :provider (đã nhập: :actual).',
            'invalid_secret_base32' => 'Khóa bí mật không phải là chuỗi Base32 hợp lệ hoặc thiếu độ hỗn loạn (entropy).',
            'replay_attack'         => 'Phát hiện cuộc tấn công phát lại (replay attack). Mã này đã được sử dụng.',
            'provider_mismatch'     => 'Khóa bí mật không khớp với nhà cung cấp :provider.',
        ],
        // 19. Tailandês
        'th_TH' => [
            'code_expired'          => 'รหัสที่ระบุหมดอายุหรือไม่ถูกต้อง',
            'invalid_code_length'   => 'รหัสต้องมีความยาวถูกต้อง :expected หลักสำหรับผู้ให้บริการ :provider (ที่ระบุ: :actual)',
            'invalid_secret_base32' => 'คีย์ลับไม่ใช่สตริง Base32 ที่ถูกต้อง หรือมีความหนาแน่นของข้อมูลไม่เพียงพอ',
            'replay_attack'         => 'ตรวจพบการโจมตีแบบ Replay รหัสนี้ถูกใช้งานไปแล้ว',
            'provider_mismatch'     => 'คีย์ลับไม่ตรงกับผู้ให้บริการ :provider',
        ],
        // 20. Indonésio
        'id_ID' => [
            'code_expired'          => 'Kode yang diberikan telah kedaluwarsa atau tidak valid.',
            'invalid_code_length'   => 'Kode harus persis :expected karakter untuk penyedia :provider (diberikan: :actual).',
            'invalid_secret_base32' => 'Kunci rahasia bukan string Base32 yang valid atau kurang entropi.',
            'replay_attack'         => 'Serangan replay terdeteksi. Kode ini sudah digunakan.',
            'provider_mismatch'     => 'Kunci rahasia tidak cocok untuk penyedia :provider.',
        ],
        // 21. Hindi
        'hi_IN' => [
            'code_expired'          => 'प्रदान किया गया कोड समाप्त हो गया है या अमान्य है।',
            'invalid_code_length'   => 'प्रदाता :provider के लिए कोड सटीक :expected अक्षरों का होना चाहिए (दिया गया: :actual)।',
            'invalid_secret_base32' => 'गुप्त कुंजी एक मान्य Base32 स्ट्रिंग नहीं है या इसमें पर्याप्त एन्ट्रापी नहीं है।',
            'replay_attack'         => 'रीप्ले हमले का पता चला। यह कोड पहले ही उपयोग किया जा चुका है।',
            'provider_mismatch'     => 'प्रदाता :provider के लिए गुप्त कुंजी बेमेल है।',
        ],
        // 22. Bengali
        'bn_BD' => [
            'code_expired'          => 'প্রদান করা কোডটির মেয়াদ শেষ হয়ে গেছে বা অকার্যকর।',
            'invalid_code_length'   => ':provider প্রোভাইডারের জন্য কোডটি ঠিক :expected অক্ষরের হতে হবে (দেওয়া হয়েছে: :actual)।',
            'invalid_secret_base32' => 'গোপন কী-টি একটি সঠিক Base32 স্ট্রিং নয় বা এতে এন্ট্রপির অভাব রয়েছে।',
            'replay_attack'         => 'রিপ্লে অ্যাটাক শনাক্ত হয়েছে। এই কোডটি আগেই ব্যবহার করা হয়েছে।',
            'provider_mismatch'     => ':provider প্রোভাইডারের সাথে গোপন কী মিলছে না।',
        ],
        // 23. Árabe (RTL)
        'ar_SA' => [
            'code_expired'          => 'الرمز المدخل منتهي الصلاحية أو غير صالح.',
            'invalid_code_length'   => 'يجب أن يتكون الرمز من :expected خانة لمزوّد الخدمة :provider (المدخل: :actual).',
            'invalid_secret_base32' => 'المفتاح السري ليس سلسلة Base32 صالحة أو يفتقر إلى العشوائية الكافية.',
            'replay_attack'         => 'تم إكتشاف هجوم إعادة استخدام الرمز (Replay Attack). هذا الرمز تم استخدامه سابقاً.',
            'provider_mismatch'     => 'المفتاح السري غير متوافق مع المزوّد :provider.',
        ],
        // 24. Persa (RTL)
        'fa_IR' => [
            'code_expired'          => 'کد وارد شده منقضی شده یا نامعتبر است.',
            'invalid_code_length'   => 'کد باید دقیقا شامل :expected کاراکتر برای ارائه دهنده :provider باشد (وارد شده: :actual).',
            'invalid_secret_base32' => 'کلید مخفی یک رشته Base32 معتبر نیست یا فاقد انتروپی کافی است.',
            'replay_attack'         => 'حمله بازپخش (Replay Attack) تشخیص داده شد. این کد قبلاً استفاده شده است.',
            'provider_mismatch'     => 'عدم تطابق کلید مخفی برای ارائه دهنده :provider.',
        ],
        // 25. Hebraico (RTL)
        'he_IL' => [
            'code_expired'          => 'הקוד שסופק פג תוקף או אינו תקין.',
            'invalid_code_length'   => 'הקוד חייב להכיל בדיוק :expected תווים עבור הספק :provider (סופק: :actual).',
            'invalid_secret_base32' => 'המפתח הסודי אינו מחרוזת Base32 תקינה או שחסרה לו אנטרופיה.',
            'replay_attack'         => 'זוהתה התקפת שחזור (Replay Attack). קוד זה כבר היה בשימוש.',
            'provider_mismatch'     => 'אי התאמה במפתח הסודי עבור הספק :provider.',
        ],
        // 26. Turco
        'tr_TR' => [
            'code_expired'          => 'Sağlanan kodun süresi dolmuş veya geçersiz.',
            'invalid_code_length'   => ':provider sağlayıcısı için kod tam olarak :expected karakter olmalıdır (sağlanan: :actual).',
            'invalid_secret_base32' => 'Gizli anahtar geçerli bir Base32 dizesi değil veya yetersiz entropiye sahip.',
            'replay_attack'         => 'Yeniden oynatma saldırısı (Replay Attack) algılandı. Bu kod zaten kullanılmış.',
            'provider_mismatch'     => ':provider sağlayıcısı için gizli anahtar uyuşmazlığı.',
        ],
        // 27. Swahili
        'sw_KE' => [
            'code_expired'          => 'Kodi iliyotolewa imหมหมisha muda wake au si sahihi.',
            'invalid_code_length'   => 'Kodi lazima iwe na herufi :expected kamili kwa mtoa huduma :provider (uliyotenda: :actual).',
            'invalid_secret_base32' => 'Ufunguo wa siri si mfuatano halali wa Base32 au unakosa entropia ya kutosha.',
            'replay_attack'         => 'Shambulio la kurudia kodi (Replay Attack) limegunduliwa. Kodi hii tayari imeshatumika.',
            'provider_mismatch'     => 'Ufunguo wa siri haufanani na mtoa huduma :provider.',
        ],
    ];

    /**
     * Define o idioma ativo do sistema i18n.
     */
    public static function setLocale(OTPLanguage $locale): void
    {
        self::$locale = $locale;
    }

    /**
     * Traduz uma chave e substitui os placeholders dinâmicos.
     *
     * @param string $key Chave de tradução
     * @param array<string, mixed> $placeholders Variáveis para interpolação
     * @return string Mensagem traduzida e formatada
     */
    public static function trans(string $key, array $placeholders = []): string
    {
        $lang = self::$locale->value;
        $message = self::$dictionary[$lang][$key] ?? self::$dictionary['en_US'][$key] ?? $key;

        foreach ($placeholders as $k => $v) {
            $message = str_replace(':' . $k, (string) $v, $message);
        }

        return $message;
    }
}
