<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pmSven
 * Date: 02.05.20
 * Time: 18:29
 */

namespace PmLogging\Models\Logging\Helper;

use PmAbstract\Components\DependencyInjection\Bridge\DoctrineEnum;

class LoggingEntity extends DoctrineEnum
{
    public const ENUM_NAME = 'pmLoggingEntity';
    public const ENUM_VERSION = 0;

    public const NONE = 'none';
    public const DETAIL = 'detail';
    public const ORDER = 'order';
    public const CUSTOMER = 'customer';

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
            self::NONE,
            self::CUSTOMER,
            self::ORDER,
            self::DETAIL
        ];
    }
}
