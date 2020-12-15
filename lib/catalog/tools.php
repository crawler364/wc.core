<?php

namespace WC\Catalog;


use Bitrix\Main\Loader;

class Tools
{
    public static function getProductRatio($productID)
    {
        Loader::includeModule('catalog');
        $ratio = \Bitrix\Catalog\MeasureRatioTable::getList([
            'select' => ['ID', 'RATIO'],
            'filter' => ['=PRODUCT_ID' => $productID],
        ])->fetch();

        return $ratio ? (float)$ratio['RATIO'] : 1.00;
    }

    public static function formatWeight($weightInGrams)
    {
        if ($weightInGrams >= 1000) {
            $weightFormatted = round($weightInGrams / 1000, 2) . ' кг';
        } else {
            $weightFormatted = round($weightInGrams, 2) . ' г';
        }
        return $weightFormatted;
    }
}