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

use Interop\Container\ContainerInterface;
use Landingpages\Options\LandingpagesOptions;

/**
 * Factory for Landingpages\Listener\InjectLandingpageParams
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write tests
 */
class InjectLandingpageParamsFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $callback = function () use ($container) {
            $this->landingpagesOptions = $container->get(LandingpagesOptions::class);
            $this->viewHelper = $container->get('ViewHelperManager')->get(
                \Landingpages\ViewHelper\Landingpage::class
            );
        };

        return new InjectLandingpageParams($callback);
    }
}
