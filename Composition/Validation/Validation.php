<?php

namespace App\Service\Composition\Validation;

use App\Service\Composition\CompositionInterface;

class Validation implements ValidationInterface
{
    /**
     * @var CompositionInterface
     */
    protected $composition;

    /**
     * Validation constructor.
     * @param CompositionInterface $composition
     */
    public function __construct(CompositionInterface $composition)
    {
        $this->composition = $composition;
    }

    /**
     * @param $item
     * @return bool
     */
    public function suitable($item)
    {
        $parameters = $this->composition->parameters();

        if (is_object($item) && $parameters->entity() !== get_class($item)) {
            return false;
        }

        if ($parameters->max() <= $this->composition->collection()->count()) {
            return false;
        }

        if ($this->composition->collection()->contains($item)) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function full()
    {
        $count = $this->composition->collection()->count();
        $parameters = $this->composition->parameters();

        return $count >= $parameters->min() && $count <= $parameters->max();
    }
}
