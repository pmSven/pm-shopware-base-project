<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pmSven
 * Date: 02.05.20
 * Time: 17:52
 */

namespace PmLogging\Models\Logging\Helper;

use PmAbstract\Components\DependencyInjection\Bridge\DoctrineEnumRegistration;

class DoctrineLoggingTypeRegistration implements DoctrineEnumRegistration
{
    /**
     * {@inheritDoc}
     */
    public function getDoctrineEnumClassName(): string
    {
        return LoggingType::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return LoggingType::ENUM_NAME;
    }

    /**
     * {@inheritDoc}
     */
    public function getVersion(): int
    {
        return LoggingType::ENUM_VERSION;
    }
}
