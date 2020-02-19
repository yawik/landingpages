<?php
/**
 * YAWIK Landingpages
 *
 * @filesource
 * @copyright 2019 Cross Solution <https://www.cross-solution.de>
 * @license MIT
 */

declare(strict_types=1);

namespace Landingpages\Listener;

use Core\EventManager\ListenerAggregateTrait;
use Landingpages\Controller\CategoryController;

use Landingpages\Entity\Category;

use Landingpages\Options\LandingpagesOptions;
use Laminas\EventManager\ListenerAggregateInterface;
use Laminas\Mvc\MvcEvent;
use Laminas\Stdlib\Parameters;

/**
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write tests
 */
class InjectLandingpageParams implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    // phpcs:ignore
    private $events = [
        [MvcEvent::EVENT_ROUTE, 'onRoute']
    ];

    /**
     *
     * @var LandingpagesOptions
     */
    private $landingpagesOptions;

    private $viewHelper;

    /**
     *
     * @var \Closure
     */
    private $lazyDependenciesCallback;

    public function __construct($callback)
    {
        $this->lazyDependenciesCallback = $callback;
    }

    public function onRoute(MvcEvent $event): void
    {
        $routeMatch = $event->getRouteMatch();

        if (!$routeMatch || 'lang/landingpages' !== $routeMatch->getMatchedRouteName()) {
            return;
        }

        $this->setLazyDependencies();
        $item = $this->landingpagesOptions->getItem($routeMatch->getParam('slug'));

        if ($item instanceof Category) {
            $routeMatch->setParam('controller', CategoryController::class);
            $routeMatch->setParam('action', 'index');

            return;
        }

        $query = new Parameters($item->getQuery());
        $query->set('clear', 1);
        $event->getRequest()->setQuery($query);
        $routeMatch->setParam('landingpage', $item->getParams());
        $this->viewHelper->setLandingpage($item);
    }

    private function setLazyDependencies()
    {
        if ($this->landingpagesOptions) {
            return;
        }

        $callback = $this->lazyDependenciesCallback;
        $callback->call($this);
    }
}
