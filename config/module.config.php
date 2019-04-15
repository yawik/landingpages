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

    'options' => [
        Options\LandingpagesOptions::class => ['options' => []],
    ],

    'view_manager' => [
        'template_map' => [
            'landingpages/buttons' => Module::VIEW_DIR . '/partial/buttons.phtml',
            'landingpages/category-view' => Module::VIEW_DIR . '/category-view.phtml',
        ],
    ],
];
