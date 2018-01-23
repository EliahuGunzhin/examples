<?php

namespace App\Service\Composition\Repository;

interface RepositoryInterface
{
    /**
     * @return void
     */
    public function receive();

    /**
     * @return void
     */
    public function store();

    /**
     * @return void
     */
    public function destroy();
}
