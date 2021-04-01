<?php

namespace WC\Core\Helpers;


use Bitrix\Main\Loader;
use CIBlockElement;

class IBlock
{
    public static function getElementByXmlId($elementXmlId, $iBlockXmlId = null): ?array
    {
        Loader::includeModule('iblock');

        if ($iBlockXmlId) {
            $filter = ['=XML_ID' => $elementXmlId, '=IBLOCK_XML_ID' => $iBlockXmlId];
        } else {
            $filter = ['=XML_ID' => $elementXmlId];
        }

        $list = CIBlockElement::GetList([], $filter, false, false, ['*']);
        if ($element = $list->GetNextElement()) {
            $fields = $element->GetFields();
            $properties = $element->GetProperties();
            return ['FIELDS' => $fields, 'PROPERTIES' => $properties];
        }

        return null;
    }

    public static function getIBlockIDByCode($code)
    {
        Loader::includeModule('iblock');

        $iBlockTable = \Bitrix\Iblock\IblockTable::getList([
            'select' => ['ID'],
            'filter' => ['CODE' => $code],
        ]);
        if ($iBlock = $iBlockTable->fetch()) {
            return $iBlock['ID'];
        }

        return null;
    }
}
