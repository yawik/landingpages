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
use Landingpages\Entity\Landingpage;
use Landingpages\Options\LandingpagesOptions;
use Laminas\View\Helper\AbstractHelper;

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

            $slug = $this->landingpages->combine($slug, ['glue' => $glue]);
        }

        if ($slug instanceof Landingpage) {
            $category = new Category('__combined__');
            $category->addLandingpage($slug);
            $slug = $category;
        }

        /** @var Category|string $slug */
        $category           = $slug instanceof Category ? $slug : $this->landingpages->getCategory($slug);
        $values['category'] = $category;

        return $this->getView()->render($partial, $values);
    }
}
