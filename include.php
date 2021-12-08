<?php

use Bitrix\Main\IO\Directory;

//region JS CORE
$kernelDir = Directory::isDirectoryExists($_SERVER['DOCUMENT_ROOT'] . '/local') ? '/local' : '/bitrix';

$arJsConfig = [
    'wc.core.ajax.component' => [
        'js' => "$kernelDir/js/wc/core/ajax.component.js",
    ],
];

foreach ($arJsConfig as $ext => $arExt) {
    \CJSCore::RegisterExt($ext, $arExt);
}
//endregion


\CUtil::InitJSCore(['jquery3', 'wc.core.ajax.component']);
