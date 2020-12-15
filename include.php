<?php

Bitrix\Main\Loader::registerAutoLoadClasses('wc.main', [
    WC\Main\Result::class => '/lib/main/result.php',
    WC\Main\Messages::class => '/lib/main/messages.php',
    WC\Catalog\Tools::class => '/lib/catalog/tools.php',
]);