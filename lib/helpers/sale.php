<?php


namespace WC\Core\Helpers;


class Sale
{
    public static function setRegistry($class, $entity)
    {
        $registry = \Bitrix\Sale\Registry::getInstance(\Bitrix\Sale\Registry::REGISTRY_TYPE_ORDER);
        $registry->set(constant("\Bitrix\Sale\Registry::$entity"), $class);
    }

    public static function getOrderPropertyItemByCode(\Bitrix\Sale\Order $order, $code)
    {
        $pc = $order->getPropertyCollection();

        foreach ($pc as $property) {
            if ($property->getField('CODE') === $code) {
                return $property;
            }
        }

        return null;
    }
}
