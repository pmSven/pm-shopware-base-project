<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pmSven
 * Date: 30.04.20
 * Time: 18:55
 */

namespace PmAbstract\Bootstrap;

use Doctrine\ORM\Tools\ToolsException;
use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Every database change needs to provide these methods
 *
 * Interface DatabaseChangeInterface
 * @package PmAbstract\Bootstrap
 */
interface DatabaseChangeInterface
{
    /**
     * Creates the new data in the database.
     *
     * @param ContainerInterface $container
     * @throws ToolsException
     * @throws Exception
     */
    public function update(ContainerInterface $container): void;

    /**
     * Removes the database changes. The database should be as if this plugin has never been installed.
     *
     * @param ContainerInterface $container
     * @throws ToolsException
     * @throws Exception
     */
    public function remove(ContainerInterface $container): void;
}
