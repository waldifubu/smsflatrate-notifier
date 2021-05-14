<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Notifier\Bridge\Smsflatrate;

//namespace App\Transport;

final class SmsFlatrateStatus
{
    public const GATEWAY = 100;
    public const DELIVERED = 101;
    public const NOTYET_DELIVERED = 102;
    public const NOT_DELIVERED = 103;
    public const TIME_EXPIRED = 104;
    public const SMSID_INVALID = 109;
    public const APIKEY_FAILED = 110;
    public const NO_CREDITS = 120;
    public const DATA_INVALID = 130;
    public const RECEIVER_INVALID = 131;

    public static function result($statuscode, $language = 'en'): string
    {
        return 'Status ' . $statuscode . ': ' . self::$messageList[$statuscode][$language];
    }

    public static array $messageList = [
        self::GATEWAY => [
            'de' => 'SMS erfolgreich an das Gateway übertragen',
            'en' => 'SMS successfully transferred to the gateway',
        ],
        self::DELIVERED => [
            'de' => 'SMS wurde zugestellt',
            'en' => 'SMS successfully dispatched',
        ],
        self::NOTYET_DELIVERED => [
            'de' => 'SMS wurde noch nicht zugestellt (z.B. Handy aus oder temporär nicht erreichbar)',
            'en' => 'SMS not delivered yet (for example mobile phone is off or network temporarily unavailable)',
        ],
        self::NOT_DELIVERED => [
            'de' => 'SMS konnte vermutlich nicht zugestellt werden (Rufnummer falsch, SIM nicht aktiv)',
            'en' => 'SMS probably not delivered (wrong number, SIMcard not active)',
        ],
        self::TIME_EXPIRED => [
            'de' => 'SMS konnte nach Ablauf von 48 Stunden noch immer nicht zugestellt werden. Aus dem Rückgabewert 102 wird nach Ablauf von 2 Tagen der Status 104.',
            'en' => 'SMS could not be delivered within 48 hours. The return value 102 changes to 104 after the 48 hours have passed.',
        ],
        self::SMSID_INVALID => [
            'de' => 'SMS ID abgelaufen oder ungültig (manuelle Status-Abfrage)',
            'en' => 'SMS ID expired or is invalid (for using manual status request)',
        ],
        self::APIKEY_FAILED => [
            'de' => 'Falscher Schnittstellen-Key oder Ihr Account ist gesperrt',
            'en' => 'Wrong Gateway-Key or your account is locked',
        ],
        self::NO_CREDITS => [
            'de' => 'Guthaben reicht nicht aus',
            'en' => 'Not enough credits',
        ],
        self::DATA_INVALID => [
            'de' => 'Falsche Datenübergabe (z.B. Absender fehlt)',
            'en' => 'Incorrect data transfer (for example the Sender-ID is missing)',
        ],
        self::RECEIVER_INVALID => [
            'de' => 'Empfänger nicht korrekt',
            'en' => 'Receiver number is not correct',
        ],
    ];
}
