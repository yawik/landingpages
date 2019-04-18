<?php
/**
 * YAWIK Landingpages
 *
 * @filesource
 * @copyright 2019 Cross Solution <https://www.cross-solution.de>
 * @license
 */

declare(strict_types=1);

namespace Landingpages\ViewHelper;

use Landingpages\Entity\Category;

use Zend\View\Helper\AbstractHelper;
use Landingpages\Options\LandingpagesOptions;

/**
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write tests
 */
class Landingpages extends AbstractHelper
{
    /**
     * Landingpages options
     * @var \Landingpages\Options\LandingpagesOptions
     */
    private $landingpages;

    /**
     * @param LandingpagesOptions $landinpages
     */
    public function __construct(LandingpagesOptions $landingpages)
    {
        $this->landingpages = $landingpages;
    }

    public function __invoke($slug = null, $partial = 'landingpages/buttons', array $values = [])
    {
        if (is_array($slug)) {
            $glue = $slug['glue'] ?? '+';
            unset($slug['glue']);

            return $this->landingpages->combine($slug, ['glue' => $glue]);
        }

        $category = $slug instanceof Category ? $slug : $this->landingpages->getCategory($slug);
        $values['category'] = $category;

        return $this->getView()->render($partial, $values);
    }
}
