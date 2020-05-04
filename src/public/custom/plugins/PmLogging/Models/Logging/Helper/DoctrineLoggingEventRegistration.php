<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pmSven
 * Date: 02.05.20
 * Time: 18:44
 */

namespace PmLogging\Models\Logging\Helper;

use PmAbstract\Components\DependencyInjection\Bridge\DoctrineEnumRegistration;

class DoctrineLoggingEventRegistration implements DoctrineEnumRegistration
{
    /**
     * {@inheritDoc}
     */
    public function getDoctrineEnumClassName(): string
    {
        return LoggingEvent::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return LoggingEvent::ENUM_NAME;
    }

    /**
     * {@inheritDoc}
     */
    public function getVersion(): int
    {
        return LoggingEvent::ENUM_VERSION;
    }
}

