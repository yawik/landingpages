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
use Landingpages\Options\LandingpagesOptions;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\Parameters;

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

        if (!$routeMatch || 'lang/landingpage' !== $routeMatch->getMatchedRouteName()) {
            return;
        }

        $this->setLazyDependencies();
        $landingpage = $this->landingpagesOptions->getLandingpage($routeMatch->getParam('slug'));

        $event->getRequest()->setQuery(new Parameters($landingpage->getQuery()));
        $routeMatch->setParam('landingpage', $landingpage->getParams());
        $this->viewHelper->setLandingpage($landingpage);
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
