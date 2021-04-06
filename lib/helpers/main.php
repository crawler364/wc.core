<?php


namespace WC\Core\Helpers;


use Bitrix\Main\Context;
use Bitrix\Main\GroupTable;
use Bitrix\Main\SiteTable;
use Bitrix\Main\UserGroupTable;
use CUser;
use CUserFieldEnum;

class Main
{
    public static function showBreadCrumbs(): bool
    {
        global $APPLICATION;
        $curDir = $APPLICATION->GetCurDir();
        switch ($curDir) {
            case preg_match('/(\/order\/)/i', $curDir):
            case preg_match('/(\/personal\/)/i', $curDir):
            case preg_match('/(^\/$)/', $curDir):
                return false;
            default:
                return true;
        }
    }

    public static function getUserGroupByGuid($guid)
    {
        $obGroupTable = GroupTable::Getlist([
            'filter' => ['STRING_ID' => $guid],
        ]);
        if ($group = $obGroupTable->fetch()) {
            return $group;
        }

        return null;
    }

    public static function getUsersInGroup($id): array
    {
        $obUserGroupTable = UserGroupTable::Getlist([
            'filter' => ['GROUP_ID' => $id],
        ]);
        while ($user = $obUserGroupTable->fetch()) {
            $users[] = $user;
        }

        return $users;
    }

    public static function getEmailsFromArray($array): string
    {
        foreach ($array as $value) {
            if (check_email($value['EMAIL'])) {
                $strEmails .= $value['EMAIL'] . ', ';
            }
        }

        return $strEmails;
    }

    public static function getUserField($field, $userId = null)
    {
        $user = new CUser();
        $userId = $userId ?: $user->GetID();

        if (($userInfo = $user::GetByID($userId)->Fetch()) && $userInfo[$field]) {
            return $userInfo[$field];
        }

        return null;
    }

    public static function getUserFieldEnum($field, $userId = null): ?array
    {
        $user = new CUser();
        $userId = $userId ?: $user->GetID();

        if (($userInfo = $user::GetByID($userId)->Fetch()) && $userInfo[$field]) {
            $cUserFieldEnum = new CUserFieldEnum();
            $dbRes = $cUserFieldEnum->GetList([], ['ID' => $userInfo[$field]]);
            if ($field = $dbRes->GetNext()) {
                return $field;
            }
        }

        return null;
    }

    public static function getSiteId(): string
    {
        if ($siteId = Context::getCurrent()->getSite()) {
            $siteId = SiteTable::getList([
                'order' => ['SORT' => 'ASC'],
                'select' => ['LID'],
            ])->fetch()['LID'];
        }

        return $siteId;
    }
}
