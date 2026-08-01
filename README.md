<p align="center">
  <br>
  <h1 align="center">🔐 OTPHP</h1>
  <p align="center">
    <b>Native, ultra-fast, and strictly typed engine for generating, validating, and rendering 2FA Tokens (TOTP/HOTP) in PHP.</b>
  </p>
  <p align="center">
    <a href="https://packagist.org/packages/pamellayamada/otphp"><img src="https://img.shields.io/packagist/v/pamellayamada/otphp.svg?style=for-the-badge&color=8B5CF6&label=Release" alt="Latest Version"></a>
    <a href="https://php.net"><img src="https://img.shields.io/badge/PHP-8.3%20%7C%208.5-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP Version"></a>
    <a href="#"><img src="https://img.shields.io/badge/Tests-100%25%20GREEN-10B981?style=for-the-badge&logo=phpunit&logoColor=white" alt="Tests Status"></a>
    <a href="LICENSE"><img src="https://img.shields.io/badge/License-MIT-F59E0B?style=for-the-badge" alt="License"></a>
  </p>
</p>

---

## ✨ Why use OTPHP?

**OTPHP** was built with a strong focus on **Developer Experience (DX)**, pure performance, and effortless integration into any modern PHP application.

* ⚡ **Absolute Performance:** Extremely low memory usage (~8 MB) and execution times in milliseconds.
* 🛡️ **Zero Heavy Dependencies:** No need for complex image libraries or extra C extensions.
* 🎯 **Strict Typing & Enums:** Full integration with PHP `BackedEnums` for Providers (`OTPProvider`) and Languages (`OTPLanguage`).
* 📱 **Multi-Provider Support:** From **Google Authenticator** and **Microsoft** to custom formats like **Steam Guard** (5 alphanumeric characters) and **Aegis/YubiKey** (8 digits).
* ⏱️ **Clock Drift Tolerance:** Precise server-client time variation control using the `window` parameter.
* 🎨 **SVG Vector Render:** Instant generation of clean vector QR codes ready for direct view injection.

---

## 📦 Installation

Install the package via [Composer](https://getcomposer.org/):

```bash
composer require pamellayamada/otphp
```

---

## 📖 Quick Start Guide

### 1. Create a Base32 Secret (RFC 4648)
Generate secure, random secrets to associate with your application users:

```php
use OTPHP\OTPHP;

// Generates a 32-byte secret in Base32 format
$secret = OTPHP::createSecret(32);

// Output example: "JBSWY3DPEHPK3PXPJBSWY3DPEHPK3PXP"
```

### 2. Generate and Validate Tokens (TOTP)

#### 🔹 Generating a Token
```php
use OTPHP\OTPHP;
use OTPHP\Enums\OTPProvider;

$secret = "JBSWY3DPEHPK3PXPJBSWY3DPEHPK3PXP";

// 6-digit Token (Default Google / Microsoft)
$code = OTPHP::generate($secret, OTPProvider::GOOGLE);
// Output: "582910"

// 5-character Alphanumeric Token (Steam Guard)
$steamCode = OTPHP::generate($secret, OTPProvider::STEAM);
// Output: "C832K"
```

#### 🔹 Validating User Input
```php
use OTPHP\OTPHP;
use OTPHP\Enums\OTPProvider;

$userInput = "582910";

$isValid = OTPHP::verify($userInput, $secret, OTPProvider::GOOGLE);

if ($isValid) {
    // Authentication successful!
}
```

### 3. Clock Drift Tolerance
Prevent validation failures when the user's device clock is slightly out of sync with the server:

```php
// window = 0: Strictly accepts the code for the exact current second/time-window
// window = 1: Accepts the current window and +/- 30 seconds of variation
$isValid = OTPHP::verify(
    code: $userInput,
    secret: $secret,
    provider: OTPProvider::GOOGLE,
    window: 1
);
```

### 4. Render Vector QR Code (SVG)
Inject the `<svg>` element directly into your HTML without relying on third-party APIs:

```php
use OTPHP\OTPHP;
use OTPHP\Enums\OTPProvider;

$svgCode = OTPHP::renderQrCodeSvg(
    account: 'user@company.com',
    secret: $secret,
    issuer: 'MyCompany',
    provider: OTPProvider::GOOGLE
);

echo $svgCode;
```

### 5. Internationalization (i18n)
Set the global locale for messages and exceptions using the `OTPLanguage` Enum:

```php
use OTPHP\OTPHP;
use OTPHP\Enums\OTPLanguage;

OTPHP::setLocale(OTPLanguage::EN_US);
```

---

## 🎛️ Supported Providers Table (OTPProvider)

| Enum Case | Provider / App | Length | Token Format |
|---|---|---|---|
| `OTPProvider::GOOGLE` | Google Authenticator | **6** | Numeric |
| `OTPProvider::MICROSOFT` | Microsoft Authenticator | **6** | Numeric |
| `OTPProvider::BITWARDEN` | Bitwarden | **6** | Numeric |
| `OTPProvider::STEAM` | Steam Guard | **5** | Alphanumeric |
| `OTPProvider::YUBIKEY` | YubiKey OTP | **8** | Numeric |
| `OTPProvider::AEGIS` | Aegis Authenticator | **8** | Numeric |

---

## 🧪 Quality & Test Suite

The repository achieves **100% test coverage** verified via **PHPUnit**:

```bash
composer test
```

```text
OTPHPTest Suite (Tests\OTPHPTestSuite)
 ✔ Generates and validates tokens with exact digit length for each provider
 ✔ Generates valid Base32 secrets (RFC 4648)
 ✔ Throws PHP ValueError when requesting byte sizes <= 0
 ✔ Renders valid SVG vector via renderQrCodeSvg
 ✔ Validates clock drift tolerance with time-windowing
 ✔ Changes locale smoothly across all cases registered in OTPLanguage Enum
 ✔ Returns false for codes containing alphabetic characters on numeric providers
 ✔ Returns false for empty code inputs

OK (16 tests, 72 assertions)
```

---

## 📄 License

This project is open-source software licensed under the [MIT License](LICENSE).

<p align="center">
<sub>Crafted with care by <b>Pamella Yamada de Araújo</b>.</sub>
</p>