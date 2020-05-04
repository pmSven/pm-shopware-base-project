<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pmSven
 * Date: 04.05.20
 * Time: 09:37
 */

use PmLogging\Models\Logging\Logging;

class Shopware_Controllers_Backend_PmLogging extends Shopware_Controllers_Backend_Application
{
    protected $model = Logging::class;
    protected $alias = 'pml';
}
