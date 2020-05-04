<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pmSven
 * Date: 04.05.20
 * Time: 08:48
 */

namespace PmAbstract\Bootstrap;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Helper class for database changes.
 *
 * Class AbstractDatabaseChange
 * @package PmAbstract\Bootstrap
 */
abstract class AbstractDatabaseChange implements DatabaseChangeInterface
{
    /**
     * @param ContainerInterface $container
     *
     * @return EntityManagerInterface
     */
    protected function getEntityManager(ContainerInterface $container): EntityManagerInterface
    {
        /** @var EntityManagerInterface $service */
        $service = $container->get('models');
        return $service;
    }
}
