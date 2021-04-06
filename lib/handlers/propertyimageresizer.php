<?php

/**
 * Ресайз дополнительных картинок элементов ИБ
 * AddEventHandler('iblock', 'OnAfterIBlockElementUpdate', [WC\Core\Handlers\PropertyImageResizer::class, 'init']);
 */


namespace WC\Core\Handlers;


class PropertyImageResizer
{
    // todo настройки в админку
    private static $propertyCode = "MORE_PHOTO";
    private static $maxWidth = 1000;
    private static $maxHeight = 1000;
    private static $resizeType = BX_RESIZE_IMAGE_PROPORTIONAL;

    public static function init($arFields): void
    {
        $needUpdate = false;
        $dbRes = \CIBlockElement::GetProperty($arFields["IBLOCK_ID"], $arFields["ID"], [], ["CODE" => self::$propertyCode]);
        while ($property = $dbRes->GetNext()) {
            if ($imgPath = \CFile::GetPath($property['VALUE'])) {
                $imgSize = getimagesize($_SERVER["DOCUMENT_ROOT"] . $imgPath);
                if ($imgSize[0] > self::$maxWidth || $imgSize[1] > self::$maxHeight) {
                    $needUpdate = true;
                    $resizeImg = \CFile::ResizeImageGet($property['VALUE'], [
                        'width' => self::$maxWidth,
                        'height' => self::$maxHeight,
                    ], self::$resizeType, true);
                    $imgFileArray[] = \CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"] . $resizeImg["src"]);
                } else {
                    $imgFileArray[] = \CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"] . $imgPath);
                }
            }
        }

        if ($imgFileArray && $needUpdate) {
            \CIBlockElement::SetPropertyValuesEx($arFields["ID"], $arFields["IBLOCK_ID"], [self::$propertyCode => $imgFileArray]);
        }
    }
}
