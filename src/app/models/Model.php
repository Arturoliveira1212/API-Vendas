<?php

namespace app\models;

use DateTime;
use JsonSerializable;
use ReflectionClass;

abstract class Model implements JsonSerializable {
    public function jsonSerialize() :mixed {
        $array = [];
        $reflection = new ReflectionClass($this);
        $properties = $reflection->getProperties();

        foreach ($properties as $property) {
            $property->setAccessible(true);
            $value = $property->getValue($this);

            // Verifique se o valor é uma instância de DateTime
            if ($value instanceof DateTime) {
                $array[$property->getName()] = $value->format('Y-m-d H:i:s');
            } else {
                $array[$property->getName()] = $value;
            }
        }

        return $array;
    }

}