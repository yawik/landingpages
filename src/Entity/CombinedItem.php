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

/**
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write tests
 */
class CombinedItem extends AbstractItem
{
    private $callback;

    public function __construct(string $slug, ?string $name = null, ?callable $callback = null)
    {
        parent::__construct($slug, $name);

        $this->callback = $callback;
    }

    public function reveal()
    {
        $cb = $this->callback;

        if (is_callable($cb)) {
            return $cb();
        }

        return null;
    }
}
