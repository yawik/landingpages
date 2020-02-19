<?php
/**
 * YAWIK Landingpages
 *
 * @filesource
 * @copyright 2019 Cross Solution <https://www.cross-solution.de>
 * @license
 */

declare(strict_types=1);

namespace Landingpages\Controller;

use Landingpages\Options\LandingpagesOptions;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

/**
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write tests
 */
class CategoryController extends AbstractActionController
{
    /**
     *
     * @var LandingpagesOptions
     */
    private $landingpages;

    /**
     * @param LandingpagesOptions $landingpages
     */
    public function __construct(LandingpagesOptions $landingpages)
    {
        $this->landingpages = $landingpages;
    }

    public function indexAction()
    {
        $category = $this->landingpages->getCategory(
            $this->params()->fromRoute('slug')
        );

        $model = new ViewModel([
            'category' => $category,
        ]);
        $model->setTemplate('landingpages/category-view');

        return $model;
    }
}
