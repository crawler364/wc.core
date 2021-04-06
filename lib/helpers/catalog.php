<?php


namespace WC\Core\Helpers;


use Bitrix\Catalog\MeasureRatioTable;
use Bitrix\Main\Loader;
use Bitrix\Sale\PriceMaths;
use CCurrencyLang;

class Catalog
{
    public static function getProductRatio($productId): float
    {
        Loader::includeModule('catalog');
        $ratio = MeasureRatioTable::getList([
            'select' => ['ID', 'RATIO'],
            'filter' => ['=PRODUCT_ID' => $productId],
        ])->fetch();

        return $ratio ? (float)$ratio['RATIO'] : 1.00;
    }

    public static function formatWeight($weightInGrams): string
    {
        if ($weightInGrams >= 1000) {
            $weightFormatted = round($weightInGrams / 1000, 2) . ' кг';
        } else {
            $weightFormatted = round($weightInGrams, 2) . ' г';
        }
        return $weightFormatted;
    }

    public static function formatPrice($price, $currency = 'RUB')
    {
        $price = PriceMaths::roundPrecision($price);
        return CCurrencyLang::CurrencyFormat($price, $currency);
    }
}
