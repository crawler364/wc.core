<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$tabControl = new \CAdminTabControl($arParams['FORM_ID'], $arResult['TABS'], false, true);
$tabControl->Begin();
?>

<form method="POST" action="<?= $APPLICATION->GetCurPageParam() ?>" enctype="multipart/form-data">
    <? foreach ($arResult['TABS'] as $tab) {
        $tabControl->BeginNextTab(['showTitle' => false]);

        __AdmSettingsDrawList('wc.core', $tab['FIELDS']);
    }

    $tabControl->Buttons();

    foreach ($arResult['BUTTONS'] as $button) {
        $behavior = $button['BEHAVIOR'] ?? null;
        $buttonName = $button['NAME'] ?? null;
        $buttonAttributes = $button['ATTRIBUTES'] ?? [];

        switch ($behavior) {
            case 'save':
                $buttonAttributes += [
                    'class' => 'adm-btn adm-btn-save ' . ($arParams['ALLOW_SAVE'] ? '' : 'adm-btn-disabled'),
                    'name' => 'save',
                    'value' => 'Y',
                    'disabled' => !$arParams['ALLOW_SAVE'],
                ];
                break;
            case 'apply':
                $buttonAttributes += [
                    'class' => 'adm-btn ' . ($arParams['ALLOW_SAVE'] ? '' : 'adm-btn-disabled'),
                    'name' => 'apply',
                    'value' => 'Y',
                    'disabled' => !$arParams['ALLOW_SAVE'],
                ];
                break;
            case 'reset':
                $buttonAttributes += [
                    'class' => 'adm-btn',
                    'type' => 'reset',
                ];
                break;
        }

        $buttonAttributesString = \WC\Core\Ui\Helpers\Attributes::stringify($buttonAttributes);
        ?>

        <button <?= $buttonAttributesString ?>><?= $buttonName ?></button>
    <? }

    $tabControl->End();
    ?>
</form>
