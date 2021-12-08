<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$APPLICATION->ShowAjaxHead();

$APPLICATION->IncludeComponent(
    $arParams['COMPONENT_NAME'],
    $arParams['COMPONENT_TEMPLATE'],
    $arParams['COMPONENT_PARAMS']
);
