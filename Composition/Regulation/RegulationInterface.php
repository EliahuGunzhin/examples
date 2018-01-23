<?php

namespace App\Service\Composition\Regulation;

use Illuminate\Database\Eloquent\Model;

interface RegulationInterface
{
    /**
     * @param Model $item
     * @return void
     */
    public function add($item);

    /**
     * @param Model $item
     * @return void
     */
    public function delete($item);

    /**
     * @return void
     */
    public function clear();
}
