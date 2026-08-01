# ⚡ OTPHP Enterprise Framework

[![Latest Stable Version](https://poser.pugx.org/PamellaYamada/otphp/v)](https://packagist.org/packages/PamellaYamada/otphp)
[![Total Downloads](https://poser.pugx.org/PamellaYamada/otphp/downloads)](https://packagist.org/packages/PamellaYamada/otphp)
[![License](https://poser.pugx.org/PamellaYamada/otphp/license)](https://packagist.org/packages/PamellaYamada/otphp)
[![PHP Version Require](https://poser.pugx.org/PamellaYamada/otphp/require/php)](https://packagist.org/packages/PamellaYamada/otphp)

The most **comprehensive, lightning-fast, zero-dependency, and internationalized** 2FA/OTP (TOTP & HOTP) framework for modern PHP (8.1+).

Built for enterprise-grade applications requiring multi-provider support, high throughput, zero memory footprint, native vector SVG QR Code generation, and dynamic localized exception messages in 27+ languages.

---

## ✨ Highlights & Features

- **🚀 Zero Dependencies:** Built strictly using native PHP extensions (`hash`, `spl`).
- **🛡️ Provider Presets:** Built-in engine specs for **Google Authenticator**, **Microsoft Authenticator**, **YubiKey**, **Steam Guard**, **Bitwarden**, and **Aegis**.
- **🌍 Native i18n Engine:** Exception messages translated dynamically into **27+ languages** (including full RTL support for Arabic, Hebrew, and Persian).
- **📱 Native SVG Vector Renderer:** Generate crisp, scaleable QR codes on-the-fly without GD or Imagick dependencies.
- **⚡ Bitwise Direct Stream Base32:** High-speed, memory-efficient Base32 encoder and decoder.
- **🔐 Recovery Code Management:** Argon2id-ready hashed backup code generation.
- **🎨 Complete IDE Metadata:** Integrated `.phpstorm.meta.php` for dynamic autocomplete on algorithms, providers, and locales.

---

## 📦 Installation

Install via [Composer](https://getcomposer.org/):

```bash
composer require PamellaYamada/otphp
