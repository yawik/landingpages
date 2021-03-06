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
    private $combined = [];
    private $parent;

    public function addCategory(Category $category): void
    {
        $this->categories[$category->getSlug()] = $category;
        $category->setParent($this);
    }

    public function addLandingpage(Landingpage $page): void
    {
        $page->setCategory($this);
        $this->landingpages[$page->getSlug()] = $page;
    }

    public function addCombined(CombinedItem $item): void
    {
        $this->combined[$item->getSlug()] = $item;
    }

    /**
     * getParent
     *
     * @return Category
     */
    public function getParent(): Category
    {
        return $this->parent;
    }

    /**
     * @param Category $parent
     */
    public function setParent(Category $parent): void
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

    public function getCombined(?string $slug = null)
    {
        if (null === $slug) {
            return $this->combined;
        }

        if (!isset($this->combined[$slug])) {
            throw new \RuntimeException(sprintf(
                'Combined item with slug "%s" not found.',
                $slug
            ));
        }

        return $this->combined[$slug];
    }
}
