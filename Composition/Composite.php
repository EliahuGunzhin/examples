<?php

namespace App\Service\Composition;

use App\Service\Composition\Exceptions\ClassNotFoundException;
use App\Service\Composition\Parameters\Parameters;

trait Composite
{
    protected $composite;

    /**
     * @return string
     */
    private static function getNamespaceSetting()
    {
        return app('config')->get('composition.namespace');
    }

    /**
     * @return string
     */
    private static function getPostfixSetting()
    {
        return app('config')->get('composition.postfix');
    }

    /**
     * @return string
     */
    private static function getEntityClassName()
    {
        $entityClass = get_called_class();
        $entityClassArray = (explode('\\', $entityClass));
        return array_pop($entityClassArray);
    }

    /**
     * @return string
     */
    protected function getClassName()
    {
        return sprintf('%s\%s%s',
            self::getNamespaceSetting(),
            self::getEntityClassName(),
            self::getPostfixSetting()
        );
    }

    /**
     * @return Composition
     * @throws ClassNotFoundException
     */
    public function composite()
    {
        if (!$this->composite instanceof Composition) {
            $compositionClassName = self::getClassName();

            if (!class_exists($compositionClassName)) {
                throw new ClassNotFoundException($compositionClassName);
            }

            /** @var DefinitionComposition $definition */
            $definition = new $compositionClassName($this);

            $this->composite = new Composition(
                new Parameters($definition->parameters()),
                $definition->relation()
            );
        }

        return $this->composite;
    }

}
