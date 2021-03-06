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

return ['router' => ['routes' => ['lang' => ['child_routes' => [

    'landingpages' => [
        'type' => 'Regex',
        'options' => [
            'regex' => '/landingpage/(?<slug>[\w_-]+)',
            'spec' => '/landingpage/%slug%',
            'defaults' => [
                'controller' => 'Jobs/Jobboard',
                'action' => 'index'
            ],
        ],
    ],
]]]]];
