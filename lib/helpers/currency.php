<?php


namespace WC\Core\Helpers;


class Currency
{
    public static function format($price, $currency = 'RUB')
    {
        $price = \Bitrix\Sale\PriceMaths::roundPrecision($price);
        return \CCurrencyLang::CurrencyFormat($price, $currency);
    }
}
