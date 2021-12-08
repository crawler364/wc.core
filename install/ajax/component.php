<?php

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
    die;
}

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();

$APPLICATION->IncludeComponent('wc:ajax.component', '', [
    'COMPONENT_NAME' => $request->get('COMPONENT_NAME'),
    'COMPONENT_TEMPLATE' => $request->get('COMPONENT_TEMPLATE'),
    'COMPONENT_PARAMS' => $request->get('COMPONENT_PARAMS'),
]);
