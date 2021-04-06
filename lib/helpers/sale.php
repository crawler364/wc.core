<?php


namespace WC\Core\Helpers;


use Bitrix\Sale\Order;
use Bitrix\Sale\Registry;

class Sale
{
    public static function setRegistry($class, $entity): void
    {
        $registry = Registry::getInstance(Registry::REGISTRY_TYPE_ORDER);
        $registry->set(constant("\Bitrix\Sale\Registry::$entity"), $class);
    }

    public static function getOrderPropertyItemByCode(Order $order, $code)
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
