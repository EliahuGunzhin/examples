<?php

namespace App\Service\Composition;

use App\Service\Composition\Parameters\ParametersInterface;
use App\Service\Composition\Regulation\RegulationInterface;
use App\Service\Composition\Repository\RepositoryInterface;
use App\Service\Composition\Validation\ValidationInterface;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

interface CompositionInterface
{
    /**
     * @return ParametersInterface
     */
    public function parameters();

    /**
     * @return Relation
     */
    public function relation();

    /**
     * @return Collection
     */
    public function collection();

    /**
     * @return RegulationInterface
     */
    public function regulation();

    /**
     * @return ValidationInterface
     */
    public function validation();

    /**
     * @return RepositoryInterface
     */
    public function session();

    /**
     * @return RepositoryInterface
     */
    public function database();
}
