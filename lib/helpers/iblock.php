<?php

namespace WC\Core\Helpers;


use Bitrix\Main\Loader;
use CIBlock;
use CIBlockElement;

class IBlock
{
    public static function getElementByXmlId($elementXmlId, $iBlockXmlId = null)
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
        $res = CIBlock::GetList([], ['CODE' => $code, "CHECK_PERMISSIONS" => "N"], false);
        if ($ar = $res->Fetch()) {
            return $ar["ID"];
        }
        return null;
    }

    public static function getOffersIBlock($iBlockXmlId)
    {
        Loader::includeModule('catalog');

        $iBlockTable = \Bitrix\Iblock\IblockTable::getList([
            'select' => ['ID'],
            'filter' => ['XML_ID' => $iBlockXmlId],
        ]);
        if (!$iBlock = $iBlockTable->fetch()) {
            return null;
        }

        $skuIBlock = \CCatalogSku::GetInfoByIBlock($iBlock['ID']);

        $iBlockTable = \Bitrix\Iblock\IblockTable::getList([
            'select' => ['XML_ID'],
            'filter' => ['ID' => $skuIBlock['IBLOCK_ID']],
        ]);
        if ($iBlock = $iBlockTable->fetch()) {
            return $iBlock;
        }

        return null;
    }
}
