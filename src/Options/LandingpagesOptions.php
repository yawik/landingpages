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
    private $levels = [];

    public function setFromArray($options): self
    {

        // Flatten array
        $landingpages = [];
        $categories = [];
        $levels = [];

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

        $flatten = function ($arr, $parent = null, $level = 0) use (&$landingpages, &$categories, &$levels, &$flatten) {
            foreach ($arr as $slug => $spec) {
                if (isset($spec['items'])) {
                    $category = new Category($slug, $spec['text'] ?? $slug);
                    $levels[$level][] = $category;
                    $categories[$slug] = $category;

                    $parent && $parent->addCategory($category);
                    $flatten($spec['items'], $category, $level + 1);
                } else {
                    $landingpage = new Landingpage($slug, $spec);
                    $landingpages[$slug] = $landingpage;

                    $parent && $parent->addLandingpage($landingpage);
                }
            }
        };
        $flatten($options);
        $this->landingpages = $landingpages;
        $this->categories   = $categories;
        $this->levels       = $levels;

        return $this;
    }

    public function getLandingpage(string $slug): Landingpage
    {
        $page = $this->landingpages[$slug] ?? null;

        if (!$page) {
            throw new \RuntimeException(sprintf(
                'Landingpage with slug "%s" not found.',
                $slug
            ));
        }

        return $page;
    }

    public function getCategories($level = 1)
    {
        return $this->levels[$level] ?? [];
    }

    public function getCategory(?string $slug): Category
    {
        $slug = $slug ?? '__TOP__';

        $category = $this->categories[$slug] ?? null;

        if (!$category) {
            throw new \RuntimeException(sprintf(
                'Category with slug "%s" not found.',
                $slug
            ));
        }

        return $category;
    }
}
