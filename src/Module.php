<?php
/**
 * YAWIK Landingpages
 *
 * @filesource
 * @copyright 2019 Cross Solution <https://www.cross-solution.de>
 * @license MIT
 */

declare(strict_types=1);

namespace Landingpages;

use Core\ModuleManager\ModuleConfigLoader;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\MvcEvent;

/**
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write tests
 */
class Module implements ConfigProviderInterface, BootstrapListenerInterface
{
    const VIEW_DIR = __DIR__ . '/../view';

    public function getConfig()
    {
        return ModuleConfigLoader::load(__DIR__ . '/../config');
    }

    /**
     * @inheritDoc
     */
    public function onBootstrap(\Zend\EventManager\EventInterface $e)
    {
        /** @var MvcEvent $e */
        $services = $e->getApplication()->getServiceManager();
        $eventManager = $e->getApplication()->getEventManager();
        $injectRouteParams = $services->get(Listener\InjectLandingpageParams::class);
        $injectRouteParams->attach($eventManager);
    }
}
