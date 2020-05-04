<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pmSven
 * Date: 04.05.20
 * Time: 08:31
 */

namespace PmAbstract\Bootstrap;

use PmAbstract\Bootstrap\Helper\ChangeType;
use PmAbstract\Bootstrap\Helper\Configuration;
use Shopware\Models\Shop\Shop;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Shopware\Models\Config\Element;
use Shopware\Models\Config\Value;

class Configurations extends AbstractDatabaseChange
{
    private const SEPARATOR = ';';

    /** @var iterable<Configurations> */
    private $configurations;

    /**
     * Configurations constructor.
     *
     * @param iterable<Configurations> $configurations
     */
    public function __construct(iterable $configurations)
    {
        $this->configurations = $configurations;
    }

    /**
     * @inheritDoc
     */
    public function update(ContainerInterface $container): void
    {
        foreach ($this->configurations as $configuration) {
            $this->updateEntry($container, $configuration);
        }
    }

    /**
     * @inheritDoc
     */
    public function remove(ContainerInterface $container): void
    {
        foreach ($this->configurations as $configuration) {
            $this->removeEntry($container, $configuration);
        }
    }

    /**
     * Updates the entry.
     *
     * @param ContainerInterface $container
     * @param Configuration      $configuration
     */
    private function updateEntry(ContainerInterface $container, Configuration $configuration): void
    {
        $entityManager = $this->getEntityManager($container);
        $configValues = $this->getConfigValues($container, $configuration->getName(), true);

        /** @var Value $configValue */
        foreach ($configValues as $configValue) {
            $currentValue = $configValue->getValue()?? '';

            if ($configuration->getChangeType() === ChangeType::APPEND) {
                if (strpos($currentValue, $configuration->getNewValue()) === false) {
                    $configValue->setValue($currentValue . self::SEPARATOR . $configuration->getNewValue());
                }
            } else {
                $configValue->setValue($configuration->getNewValue());
            }

            $entityManager->persist($configValue);
        }

        $entityManager->flush();
    }

    /**
     * Removes the values from the entries.
     *
     * @param ContainerInterface $container
     * @param Configuration      $configuration
     */
    private function removeEntry(ContainerInterface $container, Configuration $configuration): void
    {
        $entityManager = $this->getEntityManager($container);
        $configValues = $this->getConfigValues($container, $configuration->getName());

        /** @var Value $configValue */
        foreach ($configValues as $configValue) {
            if (strpos($configValue->getValue(), $configuration->getNewValue()) !== false) {
                $configValue->setValue(str_replace(self::SEPARATOR . $configuration->getNewValue(), '', $configValue->getValue()));
                $entityManager->persist($configValue);
            }
            if($configValue->getValue() === $configValue->getElement()->getValue()) {
                $entityManager->remove($configValue);
            }
        }

        $entityManager->flush();
    }

    /**
     * @param ContainerInterface $container
     * @param string             $configName
     * @param bool               $createIfNeeded
     *
     * @return Value[]
     */
    private function getConfigValues(ContainerInterface $container, string $configName, bool $createIfNeeded = false): array
    {
        $entityManager = $this->getEntityManager($container);
        $configElement = $this->getConfigElement($container, $configName);

        if (!isset($configElement)) {
            return [];
        }
        if ($createIfNeeded && count($configElement->getValues()) === 0) {
            $values = [];
            foreach ($this->getShops($container) as $shop) {
                $value = new Value();
                $value->setElement($configElement);
                $value->setValue($configElement->getValue());
                $value->setShop($shop);
                $entityManager->persist($value);
                $values[] = $value;
            }
            $entityManager->flush();
            return $values;
        }
        return $configElement->getValues()->toArray();
    }

    /**
     * Returns the config element by it's name
     *
     * @param ContainerInterface $container
     * @param string             $configName
     *
     * @return Element
     */
    private function getConfigElement(ContainerInterface $container, string $configName): ?Element
    {
        $elementRepo = $this->getEntityManager($container)
            ->getRepository(Element::class);

        /** @var Element $element */
        $element = $elementRepo->findOneBy([
            'name' => $configName
        ]);
        return $element;
    }

    /**
     * @param ContainerInterface $container
     *
     * @return Shop[]
     */
    private function getShops(ContainerInterface $container): array
    {
        return $this->getEntityManager($container)
            ->getRepository(Shop::class)
            ->findAll();
    }
}
