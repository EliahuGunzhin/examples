<?php

namespace App\Service\Composition;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;

abstract class DefinitionComposition
{
    /**
     * @var Model
     */
    protected $entity;

    /**
     * DefinitionComposition constructor.
     *
     * @param Model $entity
     */
    public function __construct(Model $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return string
     */
    public function name()
    {
        return Str::snake(str_replace('\\', '', get_class($this->entity)));
    }

    /**
     * Set relation for job with database repository
     *
     * @return Relation
     */
    abstract public function relation();

    /**
     * Set rules for job with validation
     *
     * @return array
     */
    abstract public function parameters();
}
