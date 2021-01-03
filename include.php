<?php

Bitrix\Main\Loader::registerAutoLoadClasses('wc.main', [
    WC\Main\Result::class => '/lib/main/result.php',
    WC\Main\Localization\Loc::class => '/lib/main/localization/loc.php',
    WC\Main\Tools::class => '/lib/main/tools.php',
    WC\Catalog\Tools::class => '/lib/catalog/tools.php',
    WC\Sale\Tools::class => '/lib/sale/tools.php',
    WC\IBlock\Tools::class => '/lib/iblock/tools.php',
    WC\IBlock\UniqueSymbolCode::class => '/lib/iblock/uniquesymbolcode.php',
    WC\Currency\Tools::class => '/lib/currency/tools.php',
]);