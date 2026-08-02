<?php

declare(strict_types=1);

namespace PamellaYamada\OTPHP\Enums;

enum OTPProvider: string
{
    // 🔐 Apps Autenticadores e Gerenciadores de Senha
    case GOOGLE = 'GOOGLE';
    case MICROSOFT = 'MICROSOFT';
    case AUTHY = 'AUTHY';
    case DUO = 'DUO';
    case BITWARDEN = 'BITWARDEN';
    case ONEPASSWORD = 'ONEPASSWORD';
    case LASTPASS = 'LASTPASS';
    case KEEPER = 'KEEPER';
    case DASHLANE = 'DASHLANE';
    case PROTON_PASS = 'PROTON_PASS';
    case TWOFAS = 'TWOFAS';
    case AEGIS = 'AEGIS';
    case ENTE_AUTH = 'ENTE_AUTH';
    case RAIVO = 'RAIVO';
    case FREEOTP = 'FREEOTP';

    // 🏢 Hardware, Enterprise e Identidade (IdP)
    case YUBIKEY = 'YUBIKEY';
    case OKTA = 'OKTA';
    case PING_IDENTITY = 'PING_IDENTITY';
    case CLOUDFLARE = 'CLOUDFLARE';
    case AWS = 'AWS';
    case SALESFORCE = 'SALESFORCE';

    // 🌐 Redes Sociais e Big Techs
    case GITHUB = 'GITHUB';
    case GITLAB = 'GITLAB';
    case FACEBOOK = 'FACEBOOK';
    case INSTAGRAM = 'INSTAGRAM';
    case X_TWITTER = 'X_TWITTER';
    case LINKEDIN = 'LINKEDIN';
    case DISCORD = 'DISCORD';
    case TWITCH = 'TWITCH';
    case SLACK = 'SLACK';
    case ZOOM = 'ZOOM';
    case DROPBOX = 'DROPBOX';

    // 💳 Finanças, Bancos e Cripto
    case STRIPE = 'STRIPE';
    case PAYPAL = 'PAYPAL';
    case BINANCE = 'BINANCE';
    case COINBASE = 'COINBASE';
    case KRAKEN = 'KRAKEN';
    case MERCADO_LIVRE = 'MERCADO_LIVRE';

    // 🎮 Plataformas de Games
    case STEAM = 'STEAM';
    case EPIC_GAMES = 'EPIC_GAMES';
    case NINTENDO = 'NINTENDO';
    case PLAYSTATION = 'PLAYSTATION';
    case XBOX = 'XBOX';
    case RIOT_GAMES = 'RIOT_GAMES';

    // ⚙️ Padrões Genéricos Customizáveis
    case GENERIC_6_DIGITS = 'GENERIC_6_DIGITS';
    case GENERIC_8_DIGITS = 'GENERIC_8_DIGITS';
    case GENERIC_60_SECONDS = 'GENERIC_60_SECONDS';

    /**
     * @return array{0: OTPAlgorithm, 1: int, 2: int, 3: ?string}
     */
    public function getConfig(): array
    {
        return match ($this) {
            // A Steam usa 5 caracteres de um alfabeto base customizado
            self::STEAM => [OTPAlgorithm::SHA1, 5, 30, '23456789BCDFGHJKMNPQRTVWXY'],

            // Provedores que suportam tokens de 8 dígitos por padrão
            self::YUBIKEY, self::AEGIS, self::GENERIC_8_DIGITS => [OTPAlgorithm::SHA1, 8, 30, null],

            // Padrão de ciclo longo
            self::GENERIC_60_SECONDS => [OTPAlgorithm::SHA1, 6, 60, null],

            // Default fallback: 6 dígitos, 30 segundos, SHA-1 (RFC 6238)
            default => [OTPAlgorithm::SHA1, 6, 30, null],
        };
    }
}
