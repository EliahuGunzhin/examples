<?php

namespace App\Service\Composition\Parameters;

use ArrayIterator;

class Parameters implements ParametersInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $entity;

    /**
     * @var integer
     */
    protected $min;

    /**
     * @var integer
     */
    protected $max;

    /**
     * @var boolean
     */
    protected $unique;

    public function __construct(array $settings)
    {
        $settings = new ArrayIterator($settings);

        while ($settings->valid()) {
            if (property_exists(self::class, $settings->key())) {
                $this->{$settings->key()} = $settings->current();
            }

            $settings->next();
        }
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function entity()
    {
        return $this->entity;
    }

    /**
     * @return integer
     */
    public function min()
    {
        return $this->min;
    }

    /**
     * @return integer
     */
    public function max()
    {
        return $this->max;
    }

    /**
     * @return boolean
     */
    public function unique()
    {
        return $this->unique;
    }
}
