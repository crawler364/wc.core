<?php

Bitrix\Main\Loader::registerAutoLoadClasses('wc.main', [
    WC\Main\Result::class => '/lib/main/result.php',
    WC\Main\Messages::class => '/lib/main/messages.php',
    WC\Main\Tools::class => '/lib/main/tools.php',
    WC\Catalog\Tools::class => '/lib/catalog/tools.php',
    WC\Sale\Tools::class => '/lib/sale/tools.php',
]);