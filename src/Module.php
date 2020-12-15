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
use Laminas\ModuleManager\Feature\BootstrapListenerInterface;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Core\ModuleManager\Feature\VersionProviderInterface;
use Core\ModuleManager\Feature\VersionProviderTrait;
use Laminas\Mvc\MvcEvent;

/**
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write tests
 */
class Module implements ConfigProviderInterface, BootstrapListenerInterface, VersionProviderInterface
{
    use VersionProviderTrait;

    const VERSION = '0.3.0';

    const VIEW_DIR = __DIR__ . '/../view';

    public function getConfig()
    {
        return ModuleConfigLoader::load(__DIR__ . '/../config');
    }

    /**
     * @inheritDoc
     */
    public function onBootstrap(\Laminas\EventManager\EventInterface $e)
    {
        /** @var MvcEvent $e */
        $services = $e->getApplication()->getServiceManager();
        $eventManager = $e->getApplication()->getEventManager();
        $injectRouteParams = $services->get(Listener\InjectLandingpageParams::class);
        $injectRouteParams->attach($eventManager);
    }
}
