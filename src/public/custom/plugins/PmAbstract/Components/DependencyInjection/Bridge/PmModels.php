<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pmSven
 * Date: 02.05.20
 * Time: 17:15
 */

namespace PmAbstract\Components\DependencyInjection\Bridge;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Enlight_Loader;
use IteratorAggregate;
use PmAbstract\Bootstrap\DoctrineEnums;
use Shopware\Components\DependencyInjection\Bridge\Models;
use Shopware\Components\Model\Configuration;
use Shopware\Components\Model\ModelManager;
use Shopware\Components\Model\QueryOperatorValidator;

/**
 * This decorates the @see Models by registering new types.
 *
 * Class PmModels
 * @package PmAbstract\Components\DependencyInjection\Bridge
 */
class PmModels
{
    /**
     * @var Models
     */
    private $innerService;

    /**
     * @var iterable<DoctrineEnumRegistration>
     */
    private $enums;

    /**
     * PmModels constructor.
     *
     * @param Models            $models
     * @param IteratorAggregate $enums
     */
    public function __construct(Models $models, IteratorAggregate $enums)
    {
        $this->innerService = $models;
        $this->enums = $enums;
    }

    /**
     * {@see Models::factory()}
     *
     * @param EventManager                $eventManager
     * @param Configuration               $config
     * @param Enlight_Loader              $loader
     * @param Connection                  $connection
     * @param AnnotationDriver            $modelAnnotation
     * @param QueryOperatorValidator|null $operatorValidator
     *
     * @return ModelManager
     * @throws DBALException
     */
    public function factory(
        EventManager $eventManager,
        Configuration $config,
        Enlight_Loader $loader,
        Connection $connection,
        AnnotationDriver $modelAnnotation,
        QueryOperatorValidator $operatorValidator = NULL
    ): ModelManager {
        $result = $this->innerService->factory($eventManager, $config, $loader, $connection, $modelAnnotation, $operatorValidator);

        //register types
        foreach ($this->enums as $enum) {
            /** @var DoctrineEnumRegistration $enum */
            DoctrineEnums::registerDoctrineEnum($enum, $result);
        }

        return $result;
    }
}
