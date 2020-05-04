<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pmSven
 * Date: 04.05.20
 * Time: 10:59
 */

use PmAbstract\Controllers\Backend\AbstractEnumBackendController;
use PmLogging\Models\Logging\Helper\LoggingEntity;

class Shopware_Controllers_Backend_Entity extends AbstractEnumBackendController
{
    /**
     * @inheritDoc
     */
    protected function getEnumValues(): array
    {
        return LoggingEntity::getStaticEnumValues();
    }

    protected function getFieldName(): string
    {
        return 'entity';
    }
}
