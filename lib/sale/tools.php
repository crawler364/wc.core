<?php


namespace WC\Sale;


class Tools
{
    public static function setRegistry($class, $entity)
    {
        $registry = \Bitrix\Sale\Registry::getInstance(\Bitrix\Sale\Registry::REGISTRY_TYPE_ORDER);
        $registry->set(constant("\Bitrix\Sale\Registry::$entity"), $class);
    }
}