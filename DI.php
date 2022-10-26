<?php

declare(strict_types=1);

namespace DI;

use LogicException;
use ReflectionClass;

class DI
{
    private array $specialDependenciesArray = [
        // Here go the names of special cases that cannot be instanciated normally such as imported dependencies
    ];

    private array $instanceReturnedArray = [];

    /** @return array<mixed> */
    public function getConstructArgs(string $className): array
    {
        $currentInstanceArray = [];

        $reflectionClass = new ReflectionClass($className);
        $classConstructorParameters = $reflectionClass->getConstructor()?->getParameters() ?? [];
        
        foreach($classConstructorParameters as $classConstructorParameter) {
            $classConstructorParameterTarget = $classConstructorParameter->getType()->getName();

            if (!class_exists($classConstructorParameterTarget)) {
                if (\in_array($classConstructorParameter->getName(), $this->specialDependenciesArray)) {
                    if (null === ($this->instanceReturnedArray[$classConstructorParameter->getName()] ?? null)) {
                        $this->instanceReturnedArray[$classConstructorParameter->getName()] = $this->setSpecialValue($classConstructorParameter->getName());
                    }
                    $currentInstanceArray[$classConstructorParameter->getName()] = $this->instanceReturnedArray[$classConstructorParameter->getName()];
                    continue;
                }

                if ($classConstructorParameter->isOptional()) {
                    continue;
                }
                throw new \LogicException('Impossible dependency : '.$classConstructorParameterTarget);
            }
            if (null === ($this->instanceReturnedArray[$classConstructorParameterTarget] ?? null)) {
                $this->instanceReturnedArray[$classConstructorParameterTarget] = new $classConstructorParameterTarget(...$this->getConstructArgs($classConstructorParameterTarget));
            }
            $currentInstanceArray[$classConstructorParameter->getName()] = $this->instanceReturnedArray[$classConstructorParameterTarget];
        }
        return $currentInstanceArray;
    }

    public function setSpecialValue(string $varName): mixed {
        switch($varName) {
            // Here go the special dependencies listed in the array on top of the file. You should for each case
            // return the matching instance. 
        }
    }
}