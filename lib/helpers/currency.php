<?php


namespace WC\Core\Helpers;


class Currency
{
    public static function format($price, $currency = 'RUB')
    {
        return \SaleFormatCurrency($price, $currency);
    }
}
