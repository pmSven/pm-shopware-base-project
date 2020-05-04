<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pmSven
 * Date: 02.05.20
 * Time: 12:35
 */

namespace PmLogging\Models\Logging;

use Shopware\Components\Model\ModelRepository;

class LoggingRepository extends ModelRepository
{
    protected const TABLE_NAME = 's_plugin_pm_logging';
}
