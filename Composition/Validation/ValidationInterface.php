<?php

namespace App\Service\Composition\Validation;

interface ValidationInterface
{
    /**
     * @param Object $item
     * @return boolean
     */
    public function suitable($item);

    /**
     * @return boolean
     */
    public function full();
}
