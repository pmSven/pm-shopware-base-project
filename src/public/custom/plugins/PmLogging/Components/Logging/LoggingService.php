<?php declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: pmSven
 * Date: 30.04.20
 * Time: 21:32
 */

namespace PmLogging\Components\Logging;

use Doctrine\Common\Util\Debug;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use PmLogging\Models\Logging\Helper\LoggingType;
use PmLogging\Models\Logging\Logging;

class LoggingService implements LoggingServiceInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * LoggingService constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritDoc}
     */
    public function log(object $caller, string $entity, ?int $referenceId, string $event, string $msg = '', object $data = NULL, bool $callStack = false): void
    {
        $this->createLog(LoggingType::INFO, $caller, $entity, $referenceId, $event, $msg, $data, $callStack);
    }

    /**
     * {@inheritDoc}
     */
    public function warning(object $caller, string $entity, ?int $referenceId, string $event, string $msg = '', object $data = NULL, bool $callStack = false): void
    {
        $this->createLog(LoggingType::WARNING, $caller, $entity, $referenceId, $event, $msg, $data, $callStack);
    }

    /**
     * {@inheritDoc}
     */
    public function error(object $caller, string $entity, ?int $referenceId, string $event, string $msg = '', object $data = NULL, bool $callStack = false): void
    {
        $this->createLog(LoggingType::ERROR, $caller, $entity, $referenceId, $event, $msg, $data, $callStack);
    }

    /**
     * {@inheritDoc}
     */
    public function critical(object $caller, string $entity, ?int $referenceId, string $event, string $msg = '', object $data = NULL, bool $callStack = false): void
    {
        $this->createLog(LoggingType::CRITICAL, $caller, $entity, $referenceId, $event, $msg, $data, $callStack);
    }

    /**
     * @param string      $type
     * @param object      $caller
     * @param string      $entity
     * @param int|null    $referenceId
     * @param string      $event
     * @param string      $msg
     * @param object|null $data
     * @param bool        $callStack
     *
     * @noinspection PhpDeprecationInspection
     */
    private function createLog(string $type, object $caller, string $entity, ?int $referenceId, string $event, string $msg, ?object $data, bool $callStack): void
    {
        $e = new InvalidArgumentException();

        $log = new Logging(
            $type,
            get_class($caller),
            $entity,
            $referenceId,
            $event,
            $msg,
            isset($data) ? Debug::dump($data, LoggingServiceInterface::MAX_DATA_DEPTH, false, false) : NULL,
            $callStack ? $e->getTraceAsString() : ''
        );

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }
}
