<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pmSven
 * Date: 04.05.20
 * Time: 08:50
 */

namespace PmAbstract\Components\Plugin;

use PmAbstract\Bootstrap\Helper\Attribute;
use PmAbstract\Bootstrap\Helper\Configuration;
use PmAbstract\Components\DependencyInjection\Bridge\DoctrineEnumRegistration;

/**
 * This defines the methods that should be filled to update the database when updating/installing a plugin.
 *
 * Interface PmAbstractPluginInterface
 * @package PmAbstract\Components\Plugin
 */
interface PmAbstractPluginInterface
{
    /**
     * Returns an iterable of all the enums that should be registered for Doctrone. @see DoctrineEnumRegistration
     *
     * @return iterable<DoctrineEnumRegistration>
     */
    public function getDoctrineEnums(): iterable;

    /**
     * Returns an iterable of all the models that should be registered. Use the class names of the models.
     *
     * @return iterable<string>
     */
    public function getModelClassNames(): iterable;

    /**
     * Returns an iterable of all the attributes that should be created. @see Attribute
     *
     * @return iterable<Attribute>
     */
    public function getAttributes(): iterable;

    /**
     * Returns an iterable of all the configurations that should change. @see Configuration
     *
     * @return iterable<Configuration>
     */
    public function getConfigurations(): iterable;
}
