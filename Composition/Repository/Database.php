<?php

namespace App\Service\Composition\Repository;

use App\Entities\User;
use App\Service\Composition\CompositionInterface;
use Illuminate\Support\Collection;

class Database implements RepositoryInterface
{
    /**
     * @var CompositionInterface
     */
    protected $composition;

    /**
     * Session constructor.
     * @param CompositionInterface $composition
     */
    public function __construct(CompositionInterface $composition)
    {
        $this->composition = $composition;
    }

    /**
     * @return void
     */
    public function receive()
    {
        $composition = $this->composition;
        $composition->regulation()->clear();

        $relationCollection = new Collection();

        /** @var User $item */
        foreach ($composition->relation()->get() as $item) {
            $item->setRelation('pivot', null);
            $relationCollection->push($item);
        }

        if ($relationCollection instanceof Collection) {
            $relationCollection->map(function ($item, $key) use ($composition) {
                $composition->collection()->put($key, $item);
            });
        }
    }

    /**
     * @return void
     */
    public function store()
    {
        $this->destroy();

        while ($item = $this->composition->collection()->shift()) {
            $this->composition->relation()->save($item);
        }
    }

    /**
     * @return void
     */
    public function destroy()
    {
        $this->composition->relation()->detach();
    }
}
