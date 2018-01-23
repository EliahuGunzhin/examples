<?php

namespace App\Service\Composition\Parameters;

interface ParametersInterface
{
    /**
     * @return string
     */
    public function name();

    /**
     * @return string
     */
    public function entity();

    /**
     * @return integer
     */
    public function min();

    /**
     * @return integer
     */
    public function max();

    /**
     * @return boolean
     */
    public function unique();
}
