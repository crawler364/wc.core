<?php
/** @noinspection DisconnectedForeachInstructionInspection */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$tabControl = new CAdminTabControl($arParams['FORM_ID'], $arParams['TABS']);
$tabControl->Begin();
?>
<form method="POST" action="<?= $APPLICATION->GetCurPageParam() ?>">
    <?
    echo bitrix_sessid_post();

    foreach ($arParams['TABS'] as $tab) {
        $tabControl->BeginNextTab(['showTitle' => false]);

        __AdmSettingsDrawList($arParams['MODULE_ID'], $tab['OPTIONS']);
    }

    $tabControl->Buttons(['btnApply' => false, 'btnCancel' => false, 'btnSaveAndAdd' => false]);

    $tabControl->End();
    ?>
</form>
