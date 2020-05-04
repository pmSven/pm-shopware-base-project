<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pmSven
 * Date: 04.05.20
 * Time: 10:26
 */

use PmAbstract\Controllers\Backend\AbstractEnumBackendController;
use PmLogging\Models\Logging\Helper\LoggingEvent;

class Shopware_Controllers_Backend_Event extends AbstractEnumBackendController
{
    /**
     * @inheritDoc
     */
    protected function getEnumValues(): array
    {
        return LoggingEvent::getStaticEnumValues();
    }

    protected function getFieldName(): string
    {
        return 'event';
    }
}
