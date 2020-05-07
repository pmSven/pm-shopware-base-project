<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pmSven
 * Date: 30.04.20
 * Time: 18:38
 */

namespace PmAbstract\Components\Plugin;

use Exception;
use PmAbstract\Bootstrap\Attributes;
use PmAbstract\Bootstrap\Configurations;
use PmAbstract\Bootstrap\DatabaseChangeInterface;
use PmAbstract\Bootstrap\DoctrineEnums;
use PmAbstract\Bootstrap\Models;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;
use Shopware\Components\Plugin\Context\UpdateContext;

/**
 * Class PmAbstractPlugin
 * @package PmAbstract\Components\Plugin
 */
abstract class PmAbstractPlugin extends Plugin implements PmAbstractPluginInterface
{
    /**
     * {@inheritdoc}
     * @throws Exception
     */
    public function install(InstallContext $context)
    {
        foreach ($this->getDatabaseChanges() as $databaseChange) {
            $databaseChange->update($this->container);
        }
    }

    /**
     * {@inheritdoc}
     * @throws Exception
     */
    public function update(UpdateContext $updateContext)
    {
        $this->install($updateContext);
    }

    /**
     * {@inheritdoc}
     * @throws Exception
     */
    public function uninstall(UninstallContext $uninstallContext)
    {
        if ($uninstallContext->keepUserData()) {
            return;
        }

        foreach ($this->getDatabaseChanges() as $databaseChange) {
            $databaseChange->remove($this->container);
        }
    }

    /**
     * @return iterable<DatabaseChangeInterface>
     */
    private function getDatabaseChanges(): iterable
    {
        yield new DoctrineEnums($this->getDoctrineEnums());
        yield new Models($this->getModelClassNames());
        yield new Attributes($this->getAttributes());
        yield new Configurations($this->getConfigurations());
    }
}
