<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pmSven
 * Date: 02.05.20
 * Time: 15:52
 */

namespace PmLogging\Models\Logging\Helper;

use PmAbstract\Components\DependencyInjection\Bridge\DoctrineEnum;

class LoggingType extends DoctrineEnum
{
    public const ENUM_NAME = 'pmLoggingType';
    public const ENUM_VERSION = 0;

    public const WARNING = 'warning';
    public const CRITICAL = 'critical';
    public const ERROR = 'error';
    public const INFO = 'info';

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
            self::WARNING,
            self::CRITICAL,
            self::ERROR,
            self::INFO
        ];
    }
}
