<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pmSven
 * Date: 30.04.20
 * Time: 21:18
 */

namespace PmLogging\Models\Logging;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Shopware\Components\Model\ModelEntity;

/**
 * @ORM\Entity(repositoryClass="LoggingRepository")
 * @ORM\Table(name="s_plugin_pm_logging", options={"collate"="utf8_unicode_ci"}, indexes={@ORM\Index(name="s_plugin_pm_logging__type", columns={"type"}), @ORM\Index(name="s_plugin_pm_logging__entity", columns={"entity"}), @ORM\Index(name="s_plugin_pm_logging__event", columns={"event"}), @ORM\Index(name="s_plugin_pm_logging__time", columns={"time"})})
 * @ORM\HasLifecycleCallbacks
 */
class Logging extends ModelEntity
{
    /**
     * Primary Key - autoincrement value
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="pmLoggingType", nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="caller", type="string", nullable=false, length=500)
     */
    private $caller;

    /**
     * @var int|null
     *
     * @ORM\Column(name="reference_id", type="integer", nullable=true)
     */
    private $referenceId;

    /**
     * @var string
     *
     * @ORM\Column(name="entity", type="pmLoggingEntity", nullable=false)
     */
    private $entity;

    /**
     * @var string
     *
     * @ORM\Column(name="event", type="pmLoggingEvent", nullable=false)
     */
    private $event;

    /**
     * @var string
     *
     * @ORM\Column(name="msg", type="string", nullable=false, length=500)
     */
    private $msg;

    /**
     * @var string
     *
     * @ORM\Column(name="data", type="string", nullable=true, length=5000)
     */
    private $data;

    /**
     * @var string
     *
     * @ORM\Column(name="call_stack", type="string", nullable=true, length=5000)
     */
    private $callStack;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="time", type="datetime", nullable=true)
     */
    protected $time;

    /**
     * Logging constructor.
     *
     * @param string      $type   @see LoggingType
     * @param string      $currentCaller
     * @param string      $entity @see LoggingEntity
     * @param int|null    $referenceId
     * @param string      $event  @see LoggingEvent
     * @param string      $msg
     * @param string|null $data
     * @param string|null $callStack
     *
     * @see LoggingType, LoggingEntity, LoggingEvent
     *
     */
    public function __construct(string $type, string $currentCaller, string $entity, ?int $referenceId, string $event, string $msg = '', ?string $data = '', ?string $callStack = NULL)
    {
        $this->type = $type;
        $this->caller = substr($currentCaller, 0, 498);
        $this->referenceId = $referenceId;
        $this->entity = $entity;
        $this->event = $event;
        $this->msg = substr($msg, 0, 498);
        $this->data = isset($data) ? substr($data, 0, 4998) : NULL;
        $this->callStack = isset($callStack) ? substr($callStack, 0, 4998) : NULL;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function preInit(): self
    {
        $this->time = new DateTime();
        return $this;
    }
}
