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
        Options\LandingpagesOptions::class => ['options' => [
            'text' => 'Finde jobs für dich',
            'items' => [
                'top' => [
                    'text' => 'Top',
                    'items' => [
                        'linux' => [
                            'text' => 'Jobs in Frankfurt für "linux"',
                            'query' => [
                                'q' => 'linux',
                                'l' => 'Frankfurt',
                                'd' => 20,
                            ],
                            'params' => [
                                'extra' => 'tee',
                            ],
                        ],
                        'sub-top' => [
                            'text' => 'Fancy Unterkategorie',
                            'items' => [
                                'a' => [
                                    'text' => 'A',
                                ],
                                'b' => [
                                    'text' => 'B',
                                ]
                            ]
                        ],
                        'nocheine' => [
                            'text' => 'Noch eine Kategorie',
                            'items' => [
                                'c' => ['text' => 'V'],
                            ],
                        ],
                        'letzte-page' => [],
                        'top-bottom' => [
                            'text' => 'Tob und Bottom-Jobs',
                            'combine' => ['bottom'],
                        ],
                    ],
                ],
                'bottom' => [
                    'text' => 'Bottom',
                    'items' => [
                        'middle' => [
                            'text' => 'OOps',
                            'items' => [],
                        ],
                    ],
                ],
            ],
        ]],
    ],

    'view_manager' => [
        'template_map' => [
            'landingpages/buttons' => Module::VIEW_DIR . '/partial/buttons.phtml',
            'landingpages/category-view' => Module::VIEW_DIR . '/category-view.phtml',
        ],
    ],
];
