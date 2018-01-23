<?php

namespace App\Service\Composition\Regulation;

use App\Service\Composition\CompositionInterface;
use Illuminate\Support\Collection;

class Regulation implements RegulationInterface
{
    /**
     * @var Collection
     */
    protected $composition;

    /**
     * Composition constructor.
     *
     * @param CompositionInterface $composition
     */
    public function __construct(CompositionInterface $composition)
    {
        $this->composition = $composition;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $item
     * @return void
     */
    public function add($item)
    {
        $this->composition->collection()->push($item);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $item
     * @return void
     */
    public function delete($item)
    {
        $collection = $this->composition->collection();

        $newCollection = $collection->reject(function ($currentItem) use ($item) {
            return $currentItem->getKey() == $item->getKey();
        });

        $this->clear();

        $newCollection->each(function ($item) use ($collection) {
            $collection->prepend($item);
        });
    }

    /**
     * @return void
     */
    public function clear()
    {
        $keys = $this->composition->collection()->keys();
        $this->composition->collection()->forget($keys->all());
    }
}
