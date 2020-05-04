<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pmSven
 * Date: 30.04.20
 * Time: 15:21
 */

namespace PmLogging;

use PmAbstract\Components\Plugin\PmAbstractPlugin;
use PmLogging\Models\Logging\Helper\DoctrineLoggingEntityRegistration;
use PmLogging\Models\Logging\Helper\DoctrineLoggingEventRegistration;
use PmLogging\Models\Logging\Helper\DoctrineLoggingTypeRegistration;
use PmLogging\Models\Logging\Logging;

class PmLogging extends PmAbstractPlugin
{
    /**
     * @inheritDoc
     */
    public function getDoctrineEnums(): iterable
    {
        yield new DoctrineLoggingTypeRegistration();
        yield new DoctrineLoggingEventRegistration();
        yield new DoctrineLoggingEntityRegistration();
    }

    /**
     * {@inheritDoc}
     */
    public function getModelClassNames(): iterable
    {
        yield Logging::class;
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): iterable
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getConfigurations(): iterable
    {
        return [];
    }
}
