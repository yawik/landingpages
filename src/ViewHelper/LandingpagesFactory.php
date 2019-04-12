<?php
/**
 * YAWIK Landingpages
 *
 * @filesource
 * @copyright 2019 Cross Solution <https://www.cross-solution.de>
 * @license MIT
 */

declare(strict_types=1);

namespace Landingpages\ViewHelper;

use Interop\Container\ContainerInterface;
use Landingpages\Options\LandingpagesOptions;

/**
 * Factory for Landingpages\ViewHelper\Landingpages
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write tests
 */
class LandingpagesFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        return new Landingpages(
            $container->get(LandingpagesOptions::class)
        );
    }
}
