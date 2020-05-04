<?php /** @noinspection PhpUnused */
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pmSven
 * Date: 04.05.20
 * Time: 08:34
 */

namespace PmAbstract\Bootstrap\Helper;


class Configuration
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $newValue;

    /**
     * @var string
     * @see ChangeType
     */
    private $changeType;

    /**
     * Configuration constructor.
     *
     * @param string $name
     * @param string $newValue
     * @param string $changeType
     */
    public function __construct(string $name, string $newValue, string $changeType)
    {
        $this->name = $name;
        $this->newValue = $newValue;
        $this->changeType = $changeType;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Configuration
     */
    public function setName(string $name): Configuration
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getNewValue(): string
    {
        return $this->newValue;
    }

    /**
     * @param string $newValue
     *
     * @return Configuration
     */
    public function setNewValue(string $newValue): Configuration
    {
        $this->newValue = $newValue;
        return $this;
    }

    /**
     * @return string
     */
    public function getChangeType(): string
    {
        return $this->changeType;
    }

    /**
     * @param string $changeType
     *
     * @return Configuration
     */
    public function setChangeType(string $changeType): Configuration
    {
        $this->changeType = $changeType;
        return $this;
    }
}
