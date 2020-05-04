<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pmSven
 * Date: 02.05.20
 * Time: 17:51
 */

namespace PmAbstract\Components\DependencyInjection\Bridge;

use Doctrine\DBAL\Types\Type;
use PmAbstract\Bootstrap\DoctrineEnums;

/**
 * This is a helper class to register an enumeration to doctrine.
 * This is needed since @see Type can no be created.
 *
 * Interface DoctrineEnumRegistration
 * @package PmAbstract\Components\DependencyInjection\Bridge
 */
interface DoctrineEnumRegistration
{
    /**
     * Returns the name of this enum. This can be used as the type in the doctrine annotations.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Returns the class name of some @see DoctrineEnums
     *
     * @return string
     */
    public function getDoctrineEnumClassName(): string;

    /**
     * This must be increased every time that new entries are added.
     * Otherwise doctrine won't recognize any changes.
     *
     * @return int
     */
    public function getVersion(): int;
}