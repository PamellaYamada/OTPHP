# Changelog
All notable changes to OTPHP will be documented in this file.
## [1.0.0] - 2026-08-01
### Added
 * Native TOTP and HOTP engine for PHP 8.3+.
 * PHPStan Level 8 static analysis compliance.
 * Multi-provider support (Google Authenticator, Aegis, Bitwarden, Steam Guard, etc.).
 * Native SVG QR Code generation.
 * Full PHPUnit test coverage.
   EOF
   cat << 'EOF' > .github/PULL_REQUEST_TEMPLATE.md
## Summary
## Checklist
 * [ ] Code follows PSR-12/PER guidelines (composer format).
 * [ ] PHPStan analysis passes at Level 8 (composer analyse).
 * [ ] All unit tests pass (composer check).
