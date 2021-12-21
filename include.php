<?php

//region JS CORE
$kernelDir = strpos(__DIR__, '/local/modules/wc.core') ? '/local' : '/bitrix';
$kernelDir = $_SERVER['DOCUMENT_ROOT'] . $kernelDir;

$arJsConfig = [
    'wc.core.ajax.component' => [
        'js' => "$kernelDir/js/wc/core/ajax.component.js",
    ],
];

foreach ($arJsConfig as $ext => $arExt) {
    \CJSCore::RegisterExt($ext, $arExt);
}

\CUtil::InitJSCore(['jquery3', 'wc.core.ajax.component']);
//endregion
