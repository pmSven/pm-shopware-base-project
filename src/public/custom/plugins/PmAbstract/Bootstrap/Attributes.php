<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pmSven
 * Date: 30.04.20
 * Time: 19:28
 */

namespace PmAbstract\Bootstrap;

use Doctrine\Common\Cache\Cache;
use Exception;
use PmAbstract\Bootstrap\Helper\Attribute;
use Shopware\Bundle\AttributeBundle\Service\CrudServiceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Attributes extends AbstractDatabaseChange
{
    /** @var iterable<Attribute> */
    private $attributes;

    /**
     * Attributes constructor.
     *
     * @param iterable<Attribute> $attributes
     */
    public function __construct(iterable $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * {@inheritDoc}
     */
    public function update(ContainerInterface $container): void
    {
        $tableNames = $this->addAttributes($container);
        $this->clearModelCache($container, $tableNames);
    }

    /**
     * {@inheritDoc}
     */
    public function remove(ContainerInterface $container): void
    {
        $tableNames = $this->removeAttributes($container);
        $this->clearModelCache($container, $tableNames);
    }

    /**
     * @param ContainerInterface $container
     *
     * @return string[]
     * @throws Exception
     */
    private function addAttributes(ContainerInterface $container): array
    {
        $attributeService = $this->getAttributeServiceContainerInterface($container);
        $tableNames = [];

        foreach ($this->attributes as $attribute) {
            /** @var Attribute $attribute */
            $tableNames[] = $attribute->getTable();
            $attributeService->update(
                $attribute->getTable(),
                $attribute->getColumn(),
                $attribute->getType(),
                $attribute()
            );
        }

        return $tableNames;
    }

    /**
     * @param ContainerInterface $container
     *
     * @return string[]
     * @throws Exception
     */
    private function removeAttributes(ContainerInterface $container): array
    {
        $attributeService = $this->getAttributeServiceContainerInterface($container);
        $tableNames = [];

        foreach ($this->attributes as $attribute) {
            /** @var Attribute $attribute */
            $tableNames[] = $attribute->getTable();
            $column = $attributeService->get($attribute->getTable(), $attribute->getColumn());
            if (isset($column)) {
                $attributeService->delete($attribute->getTable(), $column->getColumnName());
            }
        }

        return $tableNames;
    }

    /**
     * Clears the cache.
     *
     * @param ContainerInterface $container
     * @param array              $tableNames
     */
    private function clearModelCache(ContainerInterface $container, array $tableNames): void
    {
        $entityManager = $this->getEntityManager($container);

        /** @var Cache $metaDataCache */
        $metaDataCache = $entityManager
            ->getConfiguration()
            ->getMetadataCacheImpl();

        $metaDataCache->deleteAll();
        $entityManager->generateAttributeModels($tableNames);
    }

    /**
     * @param $container ContainerInterface
     *
     * @return CrudServiceInterface
     */
    private function getAttributeServiceContainerInterface(ContainerInterface $container): CrudServiceInterface
    {
        /** @var CrudServiceInterface $service */
        $service = $container->get('shopware_attribute.crud_service');
        return $service;
    }
}
