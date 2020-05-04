<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pmSven
 * Date: 30.04.20
 * Time: 15:21
 */

namespace PmAbstract;

use PmAbstract\Components\Plugin\PmAbstractPlugin;

class PmAbstract extends PmAbstractPlugin
{
    /**
     * @inheritDoc
     */
    public function getDoctrineEnums(): iterable
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function getModelClassNames(): iterable
    {
        return [];
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
