<?php

/**
 * Если символьный код не задан, транслетирует из названия. Проверяет код на уникальность, если не уникален пробует добавить "1".
 * Еще раз проверяет на уникальность, если опять не уникален, меняет "1" на "2" и тд.
 * AddEventHandler('iblock', 'OnBeforeIBlockElementAdd', [WC\Core\Handlers\UniqueSymbolCode::class, 'constructElement']);
 * AddEventHandler('iblock', 'OnBeforeIBlockSectionAdd', [WC\Core\Handlers\UniqueSymbolCode::class, 'constructSection']);
 * AddEventHandler('iblock', 'OnBeforeIBlockElementUpdate', [WC\Core\Handlers\UniqueSymbolCode::class, 'constructElement']);
 * AddEventHandler('iblock', 'OnBeforeIBlockSectionUpdate', [WC\Core\Handlers\UniqueSymbolCode::class, 'constructSection']);
 */

namespace WC\Core\Handlers;

use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\SectionTable;
use Cutil;
use WC\Core\Config;

class UniqueSymbolCode
{
    private static $iBlockId;
    private static $isElem = false;
    private static $isSect = false;
    private static $defaultSettings = [
        'max_len' => '100',
        'change_case' => 'L',
        'replace_space' => '-',
        'replace_other' => '',
        'delete_repeat_replace' => 'true',
    ];

    public static function constructElement(&$arFields): void
    {
        self::$isElem = true;
        self::$iBlockId = $arFields['IBLOCK_ID'];
        self::handler($arFields);
    }

    public static function constructSection(&$arFields): void
    {
        self::$isSect = true;
        self::$iBlockId = $arFields['IBLOCK_ID'];
        self::handler($arFields);
    }

    private static function handler(&$arFields): void
    {
        if ($arFields['NAME']) {
            $code = $arFields['CODE'] ?: Cutil::translit($arFields['NAME'], LANGUAGE_ID, [
                'max_len' => Config::getOption('max_len'),
                'change_case' => Config::getOption('change_case'),
                'replace_space' => Config::getOption('replace_space'),
                'replace_other' => Config::getOption('replace_other'),
                'delete_repeat_replace' => Config::getOption('delete_repeat_replace'),
            ]);

            $arFields['CODE'] = self::checkCode($code);
        }
    }

    private static function checkCode($code, $i = null): string
    {
        $filter = ['IBLOCK_ID' => self::$iBlockId, 'CODE' => $code . $i];
        $select = ['IBLOCK_ID', 'ID'];

        if (self::$isElem) {
            $res = ElementTable::getList([
                'filter' => $filter,
                'select' => $select,
            ]);
        } elseif (self::$isSect) {
            $res = SectionTable::getList([
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

    public static function setDefaultSettings(): void
    {
        foreach (self::$defaultSettings as $key => $value) {
            Config::setOption($key, $value);
        }
    }
}
