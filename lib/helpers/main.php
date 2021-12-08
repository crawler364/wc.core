<?php


namespace WC\Core\Helpers;


use Bitrix\Main\Context;
use Bitrix\Main\SiteTable;

class Main
{
    public static function getSiteId(): string
    {
        if (!$siteId = Context::getCurrent()->getSite()) {
            $siteId = SiteTable::getList([
                'order' => ['SORT' => 'ASC'],
                'select' => ['LID'],
                'cache' => ['ttl' => 86400],
            ])->fetch()['LID'];
        }

        return $siteId;
    }
}
