<?php


namespace WC\Main;


use AF\Sale\Basket;

class Toolse
{
    public static function setRegistry($class, $newClass, $entity)
    {
        $registry = $class::getInstance($class::REGISTRY_TYPE_ORDER);
        $registry->set($class::$entity, $newClass);
    }
}