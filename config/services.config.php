<?php
/**
 * YAWIK Landingpages
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @filesource
 * @copyright 2019 Cross Solution <https://www.cross-solution.de>
 * @license MIT
 */

declare(strict_types=1);

namespace Landingpages;

return [
    'service_manager' => [
        'factories' => [
            Listener\InjectLandingpageParams::class => Listener\InjectLandingpageParamsFactory::class,
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\CategoryController::class => Controller\CategoryControllerFactory::class,
        ],
    ],

    'view_helpers' => [
        'factories' => [
            ViewHelper\Landingpages::class => ViewHelper\LandingpagesFactory::class,
            ViewHelper\Landingpage::class => \Zend\ServiceManager\Factory\InvokableFactory::class,
        ],
        'aliases' => [
            'landingpages' => ViewHelper\Landingpages::class,
            'landingpage' => ViewHelper\Landingpage::class,
        ],
    ],



];
