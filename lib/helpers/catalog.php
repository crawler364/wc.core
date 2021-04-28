<?php


namespace WC\Core\Helpers;


use Bitrix\Main\Localization\Loc;
use Bitrix\Catalog\MeasureRatioTable;
use Bitrix\Main\Loader;
use Bitrix\Sale\PriceMaths;
use CCurrencyLang;

class Catalog
{
    public static function getProductRatio($productId, $ttl = 86400): float
    {
        Loader::includeModule('catalog');

        $ratio = MeasureRatioTable::getList([
            'select' => ['ID', 'RATIO'],
            'filter' => ['=PRODUCT_ID' => $productId],
            'cache' => ['ttl' => $ttl],
        ])->fetch();

        return $ratio ? (float)$ratio['RATIO'] : 1.00;
    }

    public static function formatWeight($weightInGrams): string
    {
        if ($weightInGrams >= 1000) {
            $weightFormatted = round($weightInGrams / 1000, 2) . Loc::getMessage('WC_CORE_KG');
        } else {
            $weightFormatted = round($weightInGrams, 2) . Loc::getMessage('WC_CORE_G');
        }
        return $weightFormatted;
    }

    public static function formatPrice($price, $currency = 'RUB')
    {
        $price = PriceMaths::roundPrecision($price);
        return CCurrencyLang::CurrencyFormat($price, $currency);
    }
}
