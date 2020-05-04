<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pmSven
 * Date: 04.05.20
 * Time: 11:00
 */

use PmAbstract\Controllers\Backend\AbstractEnumBackendController;
use PmLogging\Models\Logging\Helper\LoggingType;

class Shopware_Controllers_Backend_Type extends AbstractEnumBackendController
{
    /**
     * @inheritDoc
     */
    protected function getEnumValues(): array
    {
        return LoggingType::getStaticEnumValues();
    }

    protected function getFieldName(): string
    {
        return 'type';
    }
}
