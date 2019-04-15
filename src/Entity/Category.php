<?php
/**
 * YAWIK Landingpages
 *
 * @filesource
 * @copyright 2019 Cross Solution <https://www.cross-solution.de>
 * @license MIT
 */

declare(strict_types=1);

namespace Landingpages\Entity;

use Landingpages\Options\LandingpagesOptions;

/**
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write tests
 */
class Category extends AbstractItem
{
    private $categories = [];
    private $landingpages = [];
    private $parent;

    public function addCategory(Category $category): void
    {
        $this->categories[$category->getSlug()] = $category;
        $category->setParent($this);
    }

    public function addLandingpage(Landingpage $page): void
    {
        $this->landingpages[$page->getSlug()] = $page;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return array
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    public function getCategory(string $slug): Category
    {
        if (!isset($this->categories[$slug])) {
            throw new \RuntimeException(sprintf(
                'Category with slug "%s" not found.',
                $slug
            ));
        }

        return $this->categories[$slug];
    }

    /**
     * @return array
     */
    public function getLandingpages(): array
    {
        return $this->landingpages;
    }

    public function getLandingpage(string $slug): Landingpage
    {
        if (!isset($this->landingpages[$slug])) {
            throw new \RuntimeException(sprintf(
                'Category with slug "%s" not found.',
                $slug
            ));
        }

        return $this->landingpages[$slug];
    }
}
