<?php


namespace WC\IBlock;


class UniqueSymbolCode
{
    private static $arParams = [
        'max_len' => '100',
        'change_case' => 'L',
        'replace_space' => '-',
        'replace_other' => '',
        'delete_repeat_replace' => 'true',
    ];
    private static $iBlockId;
    private static $isElem;
    private static $isSect;

    public static function constructElement(&$arFields)
    {
        self::$isElem = true;
        self::handler($arFields);
    }

    public static function constructSection(&$arFields)
    {
        self::$isSect = true;
        self::handler($arFields);
    }

    private static function handler(&$arFields)
    {
        if ($arFields['NAME'] != '' && strlen($arFields['CODE']) <= 0) {
            self::$iBlockId = $arFields['IBLOCK_ID'];
            $code = \Cutil::translit($arFields['NAME'], 'ru', self::$arParams);
            $arFields['CODE'] = self::checkCode($code);
        }
    }

    private static function checkCode($code, $i = null)
    {
        $arFilter = ['IBLOCK_ID' => self::$iBlockId, 'CODE' => $code . $i];
        $arSelect = ['IBLOCK_ID', 'ID'];
        if (self::$isElem) {
            $res = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        } elseif (self::$isSect) {
            $res = \CIBlockSection::GetList([], $arFilter, false, $arSelect, false);
        }
        if ($res->GetNext()) {
            $i++;
            return self::checkCode($code, $i);
        }
        return $code . $i;
    }
}