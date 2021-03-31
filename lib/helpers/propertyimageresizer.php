<?php

/**
 * AddEventHandler('iblock', 'OnAfterIBlockElementUpdate', [WC\Core\Helpers\PropertyImageResizer::class, 'init']);
 */


namespace WC\Core\Helpers;


class PropertyImageResizer
{
    private static $propertyCode = "MORE_PHOTO";
    private static $maxWidth = 1000;
    private static $maxHeight = 1000;

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
                    ], BX_RESIZE_IMAGE_PROPORTIONAL, true);
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
