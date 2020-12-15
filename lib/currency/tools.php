<?php


namespace WC\Currency;


class Tools
{
    public static function format($price, $currency = 'RUB')
    {
        return \SaleFormatCurrency($price, $currency);
    }
}