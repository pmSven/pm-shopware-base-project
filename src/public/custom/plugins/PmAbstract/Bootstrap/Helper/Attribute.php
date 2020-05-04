<?php /** @noinspection PhpUnused */
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: pmSven
 * Date: 30.04.20
 * Time: 19:35
 */

namespace PmAbstract\Bootstrap\Helper;

class Attribute
{
    /**
     * @var string
     */
    private $table;

    /**
     * @var string
     */
    private $column;

    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $supportText;

    /**
     * @var string
     */
    public $helpText;

    /**
     * @var bool
     */
    public $translatable;

    /**
     * @var bool
     */
    public $displayInBackend;

    /**
     * @var int
     */
    public $position;

    /**
     * @var bool
     */
    public $custom;

    /**
     * @var string
     */
    private $type;

    /**
     * Attribute constructor.
     *
     * @param string $table
     * @param string $column
     * @param string $label
     * @param string $supportText
     * @param string $helpText
     * @param bool   $translatable
     * @param bool   $displayInBackend
     * @param string $type @see AttributeType
     * @param bool   $custom
     * @param int    $position
     *
     * @see AttributeType
     *
     */
    public function __construct(string $table, string $column, string $label, string $supportText, string $helpText, bool $translatable, bool $displayInBackend, string $type, bool $custom = true, int $position = 1)
    {
        $this->table = $table;
        $this->column = $column;
        $this->label = $label;
        $this->supportText = $supportText;
        $this->helpText = $helpText;
        $this->translatable = $translatable;
        $this->displayInBackend = $displayInBackend;
        $this->position = $position;
        $this->custom = $custom;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @return string
     */
    public function getColumn(): string
    {
        return $this->column;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getSupportText(): string
    {
        return $this->supportText;
    }

    /**
     * @return string
     */
    public function getHelpText(): string
    {
        return $this->helpText;
    }

    /**
     * @return bool
     */
    public function isTranslatable(): bool
    {
        return $this->translatable;
    }

    /**
     * @return bool
     */
    public function isDisplayInBackend(): bool
    {
        return $this->displayInBackend;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @return bool
     */
    public function isCustom(): bool
    {
        return $this->custom;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Returns the data needed for Shopware to define an attribute.
     *
     * @return array
     */
    public function __invoke()
    {
        return [
            'label' => $this->getLabel(),
            'supportText' => $this->getSupportText(),
            'helpText' => $this->getHelpText(),
            'translatable' => $this->isTranslatable(),
            'displayInBackend' => $this->isDisplayInBackend(),
            'position' => $this->getPosition(),
            'custom' => $this->isCustom()
        ];
    }
}
