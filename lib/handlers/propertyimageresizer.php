<?php

/**
 * Ресайз дополнительных картинок элементов ИБ
 * AddEventHandler('iblock', 'OnAfterIBlockElementUpdate', [WC\Core\Handlers\PropertyImageResizer::class, 'init']);
 */


namespace WC\Core\Handlers;


use WC\Core\Config;

class PropertyImageResizer
{
    private static $defaultSettings = [
        'propertyCode' => 'MORE_PHOTO',
        'maxWidth' => 1000,
        'maxHeight' => 1000,
        'resizeType' => 'BX_RESIZE_IMAGE_PROPORTIONAL',
    ];

    public static function init($arFields): void
    {
        $needUpdate = false;

        $dbRes = \CIBlockElement::GetProperty($arFields["IBLOCK_ID"], $arFields["ID"], [], ["CODE" => Config::getOption('propertyCode')]);
        while ($property = $dbRes->GetNext()) {
            if ($imgPath = \CFile::GetPath($property['VALUE'])) {
                $imgSize = getimagesize($_SERVER["DOCUMENT_ROOT"] . $imgPath);

                if ($imgSize[0] >  Config::getOption('maxWidth') || $imgSize[1] > Config::getOption('maxHeight')) {
                    $needUpdate = true;
                    $resizeImg = \CFile::ResizeImageGet($property['VALUE'], [
                        'width' => Config::getOption('maxWidth'),
                        'height' => Config::getOption('maxHeight'),
                    ], Config::getOption('resizeType'), true);

                    $imgFileArray[] = \CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"] . $resizeImg["src"]);
                } else {
                    $imgFileArray[] = \CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"] . $imgPath);
                }
            }
        }

        if ($imgFileArray && $needUpdate) {
            \CIBlockElement::SetPropertyValuesEx($arFields["ID"], $arFields["IBLOCK_ID"], [Config::getOption('propertyCode') => $imgFileArray]);
        }
    }

    public static function setDefaultSettings(): void
    {
        foreach (self::$defaultSettings as $key => $value) {
            Config::setOption($key, $value);
        }
    }
}
