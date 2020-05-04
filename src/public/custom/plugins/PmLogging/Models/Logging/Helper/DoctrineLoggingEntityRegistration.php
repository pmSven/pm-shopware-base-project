<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pmSven
 * Date: 02.05.20
 * Time: 18:30
 */

namespace PmLogging\Models\Logging\Helper;

use PmAbstract\Components\DependencyInjection\Bridge\DoctrineEnumRegistration;

class DoctrineLoggingEntityRegistration implements DoctrineEnumRegistration
{
    /**
     * {@inheritDoc}
     */
    public function getDoctrineEnumClassName(): string
    {
        return LoggingEntity::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return LoggingEntity::ENUM_NAME;
    }

    /**
     * {@inheritDoc}
     */
    public function getVersion(): int
    {
        return LoggingEntity::ENUM_VERSION;
    }
}
