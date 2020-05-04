<?php declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: pmSven
 * Date: 2019-30-04
 * Time: 15:33
 */

namespace PmAbstract\Bootstrap;

use Doctrine\ORM\Tools\SchemaTool;
use Generator;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Models extends AbstractDatabaseChange
{
    /** @var iterable<string> */
    private $modelClassNames;

    /**
     * Models constructor.
     *
     * @param iterable<string> $modelClassNames
     */
    public function __construct(iterable $modelClassNames)
    {
        $this->modelClassNames = $modelClassNames;
    }

    /**
     * {@inheritDoc}
     */
    public function update(ContainerInterface $container): void
    {
        $entityManager = $this->getEntityManager($container);
        $tool = new SchemaTool($entityManager);
        $classes = $this->getClassesMetaData($container);
        $schemaManager = $entityManager
            ->getConnection()
            ->getSchemaManager();

        foreach ($classes as $class) {
            if ($schemaManager->tablesExist([$class->getTableName()])) {
                $tool->updateSchema([$class], true);
            } else {
                $tool->createSchema([$class]);
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function remove(ContainerInterface $container): void
    {
        $tool = new SchemaTool($this->getEntityManager($container));
        $classes = $this->getClassesMetaData($container);

        $tool->dropSchema(iterator_to_array($classes));
    }

    /**
     * Returns the Class Meta Data for the provided class names.
     *
     * @param ContainerInterface $container
     *
     * @return Generator
     */
    private function getClassesMetaData(ContainerInterface $container): Generator
    {
        $entityManager = $this->getEntityManager($container);

        foreach ($this->modelClassNames as $modelClassName) {
            yield $entityManager->getClassMetadata($modelClassName);
        }
    }
}
