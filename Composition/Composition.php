<?php

namespace App\Service\Composition;

use App\Service\Composition\Parameters\Parameters;
use App\Service\Composition\Parameters\ParametersInterface;
use App\Service\Composition\Regulation\Regulation;
use App\Service\Composition\Repository\Database;
use App\Service\Composition\Repository\Session;
use App\Service\Composition\Validation\Validation;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

class Composition implements CompositionInterface
{
    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var Parameters
     */
    protected $parameters;

    /**
     * @var Relation
     */
    protected $relation;

    /**
     * Composition constructor.
     *
     * @param ParametersInterface $parameters
     * @param Relation $relation
     */
    public function __construct(ParametersInterface $parameters, Relation $relation)
    {
        $this->parameters = $parameters;
        $this->relation = $relation;
        $this->collection = new Collection();
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->collection;
    }

    /**
     * @return Parameters
     */
    public function parameters()
    {
        return $this->parameters;
    }

    /**
     * @return Relation
     */
    public function relation()
    {
        return $this->relation;
    }

    /**
     * @return Validation
     */
    public function validation()
    {
        return new Validation($this);
    }

    /**
     * @return Session
     */
    public function session()
    {
        return new Session($this);
    }

    /**
     * @return Database
     */
    public function database()
    {
        return new Database($this);
    }

    /**
     * @return Regulation
     */
    public function regulation()
    {
        return new Regulation($this);
    }
}
