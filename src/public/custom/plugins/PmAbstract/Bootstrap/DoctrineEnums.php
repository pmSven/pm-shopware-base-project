<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pmSven
 * Date: 02.05.20
 * Time: 18:03
 */

namespace PmAbstract\Bootstrap;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;
use PmAbstract\Components\DependencyInjection\Bridge\DoctrineEnumRegistration;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DoctrineEnums extends AbstractDatabaseChange
{
    /**
     * @var iterable<DoctrineEnumRegistration>
     */
    private $doctrineEnumRegistrations;

    /**
     * DoctrineEnums constructor.
     *
     * @param iterable<DoctrineEnumRegistration> $doctrineEnumRegistrations
     */
    public function __construct(iterable $doctrineEnumRegistrations)
    {
        $this->doctrineEnumRegistrations = $doctrineEnumRegistrations;
    }

    /**
     * Register the types to doctrine and DBAL.
     *
     * @param DoctrineEnumRegistration $enum
     * @param EntityManagerInterface   $entityManager
     *
     * @throws DBALException
     */
    public static function registerDoctrineEnum(DoctrineEnumRegistration $enum, EntityManagerInterface $entityManager): void
    {
        if (Type::hasType($enum->getName())) {
            Type::overrideType($enum->getName(), $enum->getDoctrineEnumClassName());
        } else {
            Type::addType($enum->getName(), $enum->getDoctrineEnumClassName());
        }
        $entityManager->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping($enum->getName() . '_' . $enum->getVersion(), $enum->getName());
    }

    /**
     * @inheritDoc
     */
    public function update(ContainerInterface $container): void
    {
        foreach ($this->doctrineEnumRegistrations as $doctrineEnumRegistration) {
            self::registerDoctrineEnum($doctrineEnumRegistration, $this->getEntityManager($container));
        }
    }

    /**
     * @inheritDoc
     */
    public function remove(ContainerInterface $container): void
    {
        $this->update($container);
    }
}
