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

use Zend\View\Helper\AbstractHelper;

/**
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write tests
 */
class Landingpage extends AbstractHelper
{
    /**
     *
     * @var \Landingpages\Entity\Landingpage
     */
    private $landingpage;

    public function __invoke(): ?self
    {
        return $this->landingpage ? $this : null;
    }

    /**
     * @param mixed $landingpage
     */
    public function setLandingpage($landingpage)
    {
        $this->landingpage = $landingpage;
    }

    public function isCombined()
    {
        return false !== strpos($this->landingpage->getSlug(), '--');
    }

    public function __call($method, $args)
    {
        $cb = [$this->landingpage, $method];

        if (is_callable($cb)) {
            return $cb(...$args);
        }
    }
}
