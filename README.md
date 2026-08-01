
# OTPHP 🔑

[![Latest Version on Packagist](https://img.shields.io/packagist/v/pamellayamada/otphp.svg?style=flat-square)](https://packagist.org/packages/pamellayamada/otphp)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/PamellaYamada/otphp/ci.yml?branch=main&label=tests&style=flat-square)](https://github.com/PamellaYamada/otphp/actions)
[![PHPStan Level 8](https://img.shields.io/badge/PHPStan-Level%208-brightgreen.svg?style=flat-square)](https://phpstan.org/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=flat-square)](LICENSE)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D8.3-blue.svg?style=flat-square)](composer.json)

Native, high-performance, zero-dependency, internationalized, and strictly typed OTP (TOTP/HOTP) authentication engine for **PHP 8.3+**.

---

## ⚡ Key Features

- 🚀 **Zero Dependencies:** Built entirely with native PHP features.
- 🎯 **Strictly Typed & PSR Compliant:** PHPStan Level 8 static analysis and PSR-12/PER compliant.
- 🌐 **Internationalization (I18n):** Native multi-language support for exception messages and responses.
- 📱 **Native QR Code:** Renders clean SVG vector tags without external heavy image libraries (GD/Imagick).
- ⚙️ **Multi-Provider Support:** Pre-configured settings for Google Authenticator, Microsoft, Bitwarden, Steam Guard, YubiKey, Aegis, and more.

---

## 📦 Installation

Install the package via Composer:

```bash
composer require pamellayamada/otphp

```
## 🚀 Usage
### 1. Generating a Base32 Secret
```php
use PamellaYamada\OTPHP\OTPHP;

// Generate a secure Base32 secret key
$secret = OTPHP::createSecret(32); 

```
### 2. Generating an OTP Code (TOTP)
```php
use PamellaYamada\OTPHP\OTPHP;
use PamellaYamada\OTPHP\Enums\OTPProvider;

// Generate the current 6-digit time-based code (Default: Google Authenticator)
$code = OTPHP::generate($secret);

// Or specify a target provider (e.g., Aegis with 8 digits)
$aegisCode = OTPHP::generate($secret, OTPProvider::AEGIS);

```
### 3. Verifying User Input
```php
use PamellaYamada\OTPHP\OTPHP;

$userCode = '123456';

// Verify the code considering time drift (window parameter)
$isValid = OTPHP::verify($userCode,$secret, window: 1);

if ($isValid) {
    // Authentication successful
}

```
### 4. Rendering an SVG QR Code
```php
use PamellaYamada\OTPHP\OTPHP;

// Returns a clean <svg> string ready to embed into HTML, Blade, or PHP views
$svgXml = OTPHP::renderQrCodeSvg(
    secret: $secret,
    accountName: 'user@email.com',
    issuer: 'MyApp',
    sizePixels: 200
);

echo $svgXml;

```
### 5. Changing the Locale (I18n)
```php
use PamellaYamada\OTPHP\OTPHP;
use PamellaYamada\OTPHP\Enums\OTPLanguage;

// Set global locale for exception messages and output
OTPHP::setLocale(OTPLanguage::EN_US);

```
## 🛠️ Development & Quality Assurance
This repository includes Composer scripts to ensure code quality:
```bash
# Run code formatting (Pint), static analysis (PHPStan Level 8), and unit tests (PHPUnit)
composer check

# Run unit tests only
composer test

# Format code using Laravel Pint
composer format

# Run PHPStan analysis
composer analyse

```
## 📄 License
This project is open-sourced software licensed under the MIT License.
