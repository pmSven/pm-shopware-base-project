<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pmSven
 * Date: 02.05.20
 * Time: 18:43
 */

namespace PmLogging\Models\Logging\Helper;

use PmAbstract\Components\DependencyInjection\Bridge\DoctrineEnum;

class LoggingEvent extends DoctrineEnum
{
    public const ENUM_NAME = 'pmLoggingEvent';
    public const ENUM_VERSION = 0;

    public const TEST = 'test';

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return self::ENUM_NAME;
    }

    /**
     * {@inheritDoc}
     */
    public function getEnumValues(): array
    {
        return self::getStaticEnumValues();
    }

    public static function getStaticEnumValues(): array
    {
        return [
            self::TEST
        ];
    }
}
