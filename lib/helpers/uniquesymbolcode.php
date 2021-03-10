<?php

/**
 * Если символьный код не здан, транслетирует из названия. Проверяет код на уникальность, если не уникален пробует добавить "1".
 * Еще раз проверяет на уникальность, если опять не уникален, меняет "1" на "2" и тд.
 * AddEventHandler('iblock', 'OnBeforeIBlockElementAdd', [WC\Core\Helpers\UniqueSymbolCode::class, 'constructElement']);
 * AddEventHandler('iblock', 'OnBeforeIBlockSectionAdd', [WC\Core\Helpers\UniqueSymbolCode::class, 'constructSection']);
 * AddEventHandler('iblock', 'OnBeforeIBlockElementUpdate', [WC\Core\Helpers\UniqueSymbolCode::class, 'constructElement']);
 * AddEventHandler('iblock', 'OnBeforeIBlockSectionUpdate', [WC\Core\Helpers\UniqueSymbolCode::class, 'constructSection']);
 */

namespace WC\Core\Helpers;


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
        if ($arFields['NAME']) {
            self::$iBlockId = $arFields['IBLOCK_ID'];
            $code = $arFields['CODE'] ?: \Cutil::translit($arFields['NAME'], 'ru', self::$arParams);
            $arFields['CODE'] = self::checkCode($code);
        }
    }

    private static function checkCode($code, $i = null)
    {
        $filter = ['IBLOCK_ID' => self::$iBlockId, 'CODE' => $code . $i];
        $select = ['IBLOCK_ID', 'ID'];
        if (self::$isElem) {
            $res = \Bitrix\Iblock\ElementTable::getList([
                'filter' => $filter,
                'select' => $select,
            ]);
        } elseif (self::$isSect) {
            $res = \Bitrix\Iblock\SectionTable::getList([
                'filter' => $filter,
                'select' => $select,
            ]);
        }
        if ($res->fetch()) {
            $i++;
            return self::checkCode($code, $i);
        }
        return $code . $i;
    }
}
