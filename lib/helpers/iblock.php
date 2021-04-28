<?php


namespace WC\Core\Helpers;


use Bitrix\Iblock\IblockTable;
use Bitrix\Main\Loader;

class IBlock
{
    public static function getIBlockIdByCode($code)
    {
        Loader::includeModule('iblock');

        $iBlockTable = IblockTable::getList([
            'select' => ['ID'],
            'filter' => ['=CODE' => $code],
            'cache' => ['ttl' => 86400],
        ]);
        if ($iBlock = $iBlockTable->fetch()) {
            return $iBlock['ID'];
        }

        return null;
    }
}
