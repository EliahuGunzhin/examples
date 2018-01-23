<?php

namespace App\Service\Composition\Repository;

use App\Service\Composition\CompositionInterface;
use Illuminate\Support\Collection;

class Session implements RepositoryInterface
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
     * @return string
     */
    protected function key()
    {
        return sprintf('%s_%s', 'composition', $this->composition->parameters()->name());
    }

    /**
     * @return void
     */
    public function receive()
    {
        $composition = $this->composition;
        $composition->regulation()->clear();

        $sessionCollection = session($this->key(), new Collection());

        if ($sessionCollection instanceof Collection) {
            $sessionCollection->map(function ($item, $key) use ($composition) {
                $composition->collection()->put($key, $item);
            });
        }
    }

    /**
     * @return void
     */
    public function store()
    {
        $storedCollection = new Collection($this->composition->collection()->all());

        session()->forget($this->key());
        session()->put($this->key(), $storedCollection);
    }

    /**
     * @return void
     */
    public function destroy()
    {
        session()->forget($this->key());
    }
}
