<?php declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: pmSven
 * Date: 30.04.20
 * Time: 21:33
 */

namespace PmLogging\Components\Logging;

/**
 * Defines a service to log data to the database.
 *
 * Interface LoggingServiceInterface
 * @package PmLogging\Components\Logging
 * id = pm.logging.logging_service
 */
interface LoggingServiceInterface
{
    /**
     * @var int
     */
    public const MAX_DATA_DEPTH = 4;

    /**
     * Create a log with the level 'info'.
     *
     * @param object      $caller      This should always be '$this'.
     * @param string      $entity      The entity that will be changed.
     * @param int|null    $referenceId The id of the entity that will be changed. Can be null.
     * @param string      $event       The event that is being triggered.
     * @param string      $msg         The message that you want to log
     * @param object|NULL $data        Pass any object here to deserialize it, using a depth of @see self::MAX_DATA_DEPTH and 5000 characters.
     * @param bool        $callStack   Activate this to add a callStack to the log. Use this rarely.
     */
    public function log(object $caller, string $entity, ?int $referenceId, string $event, string $msg = '', object $data = NULL, bool $callStack = false): void;

    /**
     * Create a log with the level 'warning'.
     *
     * @param object      $caller      This should always be '$this'.
     * @param string      $entity      The entity that will be changed.
     * @param int|null    $referenceId The id of the entity that will be changed. Can be null.
     * @param string      $event       The event that is being triggered.
     * @param string      $msg         The message that you want to log
     * @param object|NULL $data        Pass any object here to deserialize it, using a depth of @see self::MAX_DATA_DEPTH and 5000 characters.
     * @param bool        $callStack   Activate this to add a callStack to the log. Use this rarely.
     */
    public function warning(object $caller, string $entity, ?int $referenceId, string $event, string $msg = '', object $data = NULL, bool $callStack = false): void;

    /**
     * Create a log with the level 'error'.
     *
     * @param object      $caller      This should always be '$this'.
     * @param string      $entity      The entity that will be changed.
     * @param int|null    $referenceId The id of the entity that will be changed. Can be null.
     * @param string      $event       The event that is being triggered.
     * @param string      $msg         The message that you want to log
     * @param object|NULL $data        Pass any object here to deserialize it, using a depth of @see self::MAX_DATA_DEPTH and 5000 characters.
     * @param bool        $callStack   Activate this to add a callStack to the log. Use this rarely.
     */
    public function error(object $caller, string $entity, ?int $referenceId, string $event, string $msg = '', object $data = NULL, bool $callStack = false): void;

    /**
     * Create a log with the level 'critical'.
     *
     * @param object      $caller      This should always be '$this'.
     * @param string      $entity      The entity that will be changed.
     * @param int|null    $referenceId The id of the entity that will be changed. Can be null.
     * @param string      $event       The event that is being triggered.
     * @param string      $msg         The message that you want to log
     * @param object|NULL $data        Pass any object here to deserialize it, using a depth of @see self::MAX_DATA_DEPTH and 5000 characters.
     * @param bool        $callStack   Activate this to add a callStack to the log. Use this rarely.
     */
    public function critical(object $caller, string $entity, ?int $referenceId, string $event, string $msg = '', object $data = NULL, bool $callStack = false): void;
}
