<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pmSven
 * Date: 04.05.20
 * Time: 09:39
 */

namespace PmLogging\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Template_Manager;

class Backend implements SubscriberInterface
{
    /**
     * @var Enlight_Template_Manager
     */
    private $templateManager;

    /**
     * Backend constructor.
     *
     * @param Enlight_Template_Manager $templateManager
     */
    public function __construct(Enlight_Template_Manager $templateManager)
    {
        $this->templateManager = $templateManager;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'Enlight_Controller_Dispatcher_ControllerPath_Backend_PmLogging' => 'onGetControllerPathBackend'
        ];
    }


    /**
     * Register the backend controller
     *
     * @return  string
     */
    public function onGetControllerPathBackend(): string
    {
        $this->templateManager->addTemplateDir(__DIR__ . '/../Resources/views/');

        return __DIR__ . '/../Controllers/Backend/PmLogging.php';
    }
}
