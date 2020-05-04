<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pmSven
 * Date: 02.05.20
 * Time: 15:43
 */

namespace PmAbstract\Components\DependencyInjection\Bridge;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use InvalidArgumentException;

/**
 * This defines an class that can be used for doctrine and DBAL enums.
 *
 * Class DoctrineEnum
 * @package PmAbstract\Components\DependencyInjection\Bridge
 */
abstract class DoctrineEnum extends Type
{
    abstract public function getName(): string;

    abstract public function getEnumValues(): array;

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        $values = array_map(static function ($val) {
            return "'{$val}'";
        }, $this->getEnumValues());

        return 'ENUM(' . implode(', ', $values) . ')';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!in_array($value, $this->getEnumValues(), true)) {
            throw new InvalidArgumentException("Invalid '{$this->getName()}' value.");
        }

        return $value;
    }
}
