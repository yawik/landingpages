<?php
/**
 * YAWIK Landingpages
 *
 * @filesource
 * @copyright 2019 Cross Solution <https://www.cross-solution.de>
 * @license MIT
 */

declare(strict_types=1);

namespace Landingpages\Options;

use Landingpages\Entity\Category;
use Landingpages\Entity\CombinedItem;
use Landingpages\Entity\Landingpage;
use Zend\Stdlib\AbstractOptions;

/**
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write tests
 */
class LandingpagesOptions extends AbstractOptions
{
    /**
     * The landingpages specification
     *
     * @see landingpages.config.php.dist
     * @var array
     */
    private $landingpages = [];
    private $categories = [];
    private $combine = [];

    public function setFromArray($options): self
    {

        // Flatten array
        $landingpages = [];
        $categories = [];
        $combine = [];

        if (!isset($options['items'])) {
            $options = [
                '__TOP__' => [
                    'text' => 'Landingpages',
                    'items' => $options,
                ],
            ];
        } else {
            $options = [
                '__TOP__' => $options,
            ];
        }

        $flatten = function ($arr, $parent = null, $level = 0) use (&$landingpages, &$categories, &$combine, &$flatten) {
            foreach ($arr as $slug => $spec) {
                switch (true) {
                    case isset($spec['combine']):
                        //phpcs:ignore
                        $item = new CombinedItem($slug, $spec['text'] ?? null, function () use ($spec) { return $this->combine($spec['combine'], $spec); });
                        $combine[$slug] = $spec;
                        $parent && $parent->addCombined($item);
                        break;

                    case isset($spec['items']):
                        $category = new Category($slug, $spec['text'] ?? $slug);
                        $categories[$slug] = $category;

                        $parent && $parent->addCategory($category);
                        $flatten($spec['items'], $category, $level + 1);
                        break;

                    default:
                        $landingpage = new Landingpage($slug, $spec);
                        $landingpages[$slug] = $landingpage;

                        $parent && $parent->addLandingpage($landingpage);
                        break;
                }
            }
        };
        $flatten($options);

        $this->landingpages = $landingpages;
        $this->categories   = $categories;
        $this->combine      = $combine;

        return $this;
    }

    public function getLandingpage(string $slug): Landingpage
    {
        $page = $this->getItem($slug);

        if (!$page) {
            throw new \RuntimeException(sprintf(
                'Landingpage with slug "%s" not found.',
                $slug
            ));
        }

        return $page;
    }

    public function getCategory(?string $slug): Category
    {
        $slug = $slug ?? '__TOP__';

        $category = $this->getItem($slug, Category::class);

        if (!$category) {
            throw new \RuntimeException(sprintf(
                'Category with slug "%s" not found.',
                $slug
            ));
        }

        return $category;
    }

    public function getItem($slug, $type = null)
    {
        switch (true) {
            case false !== strpos($slug, '--'):
                $item = $this->combine(explode('--', $slug));
                break;

            case isset($this->combine[$slug]):
                $item = $this->combine(
                    $this->combine[$slug]['combine'],
                    [
                        'glue' => $this->combine[$slug]['glue'] ?? '+',
                    ]
                );
                if (isset($this->combine[$slug]['text'])) {
                    $item->setName($this->combine[$slug]['text']);
                }
                break;

            default:
                $item = $this->categories[$slug] ?? $this->landingpages[$slug] ?? null;
                break;
        }

        if ($type && $item && !$item instanceof $type) {
            return null;
        }

        return $item;
    }

    private function addItem($category, $item)
    {
        if ($item instanceof Category) {
            $category->addCategory($item);
            return;
        }

        if ($item instanceof Landingpage) {
            $category->addLandingpage($item);
            return;
        }

        return;
    }

    private function isAlreadyCombined($item1, $item2)
    {
        $slug1     = explode('--', $item1->getSlug());
        $slug2     = explode('--', $item2->getSlug());
        $intersect = array_intersect($slug1, $slug2);

        return !empty($intersect);
    }

    public function combine(array $combine, array $options = [])
    {
        $glue = ' ' . ($options['glue'] ?? '+') . ' ';

        $combine = array_map(
            function ($item) {
                return is_object($item) ? $item : $this->getItem($item);
            },
            $combine
        );

        if (1 >= count($combine)) {
            return $combine[0];
        }

        $item1 = array_shift($combine);
        $item2 = array_shift($combine);

        if ($this->isAlreadyCombined($item1, $item2)) {
            array_unshift($combine, $item1);
            return $this->combine($combine, $options);
        }

        switch (true) {
            case $item1 instanceof Category && $item2 instanceof Category:
                $result = new Category(
                    $item1->getSlug() . '--' . $item2->getSlug(),
                    $item1->getName() . $glue . $item2->getName()
                );

                foreach ($item1->getCategories() as $category1) {
                    foreach ($item2->getCategories() as $category2) {
                        $merged = $this->combine([$category1, $category2], $options);
                        $this->addItem($result, $merged);
                    }
                    foreach ($item2->getLandingpages() as $landingpage2) {
                        $merged = $this->combine([$category1, $landingpage2], $options);
                        $this->addItem($result, $merged);
                    }
                }

                foreach ($item1->getLandingpages() as $landingpage1) {
                    foreach ($item2->getCategories() as $category2) {
                        $merged = $this->combine([$landingpage1, $category2], $options);
                        $this->addItem($result, $merged);
                    }
                    foreach ($item2->getLandingpages() as $landingpage2) {
                        $merged = $this->combine([$landingpage1, $landingpage2], $options);
                        $this->addItem($result, $merged);
                    }
                }
                break;

            case $item1 instanceof Category && $item2 instanceof Landingpage:
                $result = new Category(
                    $item1->getSlug() . '--' . $item2->getSlug(),
                    $item1->getName() . $glue . $item2->getname()
                );

                foreach ($item1->getCategories() as $category1) {
                    $merged = $this->combine([$category1, $item2], $options);
                    $this->addItem($result, $merged);
                }
                foreach ($item1->getLandingpages() as $landingpage1) {
                    $merged = $this->combine([$landingpage1, $item2], $options);
                    $this->addItem($result, $merged);
                }

                break;

            case $item1 instanceof Landingpage && $item2 instanceof Category:
                $result = new Category(
                    $item1->getSlug() . '--' . $item2->getSlug(),
                    $item1->getName() . $glue . $item2->getname()
                );

                foreach ($item2->getCategories() as $category2) {
                    $merged = $this->combine([$item1, $category2], $options);
                    $this->addItem($result, $merged);
                }
                foreach ($item2->getLandingpages() as $landingpage2) {
                    $merged = $this->combine([$item1, $landingpage2], $options);
                    $this->addItem($result, $merged);
                }
                break;

            case $item1 instanceof Landingpage && $item2 instanceof Landingpage:
                $result = new Landingpage(
                    $item1->getSlug() . '--' . $item2->getSlug(),
                    [
                        'text' => $item1->getName() . $glue . $item2->getName(),
                        'params' => array_merge($item1->getParams(), $item2->getParams())
                    ]
                );
                $query1 = $item1->getQuery();
                $query = array_merge($query1, $item2->getQuery());

                if (isset($query1['q']) && $query1['q'] != $query['q']) {
                    $query['q'] = $query1['q'] . ' ' . $query['q'];
                }

                $result->setQuery($query);
                break;

            default:
                throw new \UnexpectedValueException('Can only join Categories or Landingpages.');
        }

        array_unshift($combine, $result);
        return $this->combine($combine, $options);
    }
}
