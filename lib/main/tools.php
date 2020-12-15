<?php


namespace WC\Main;


use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Web\Json;

class Tools
{
    public static function showBreadCrumbs($APPLICATION)
    {
        $curDir = $APPLICATION->GetCurDir();
        switch ($curDir) {
            case (preg_match('/(\/order\/)/i', $curDir) ? true : false):
            case (preg_match('/(\/personal\/)/i', $curDir) ? true : false):
            case (preg_match('/(^\/$)/', $curDir) ? true : false):
                return false;
            default:
                return true;
        }
    }

    public static function getUserGroupByGuid($guid)
    {
        $obGroupTable = \Bitrix\Main\GroupTable::Getlist([
            'filter' => ['STRING_ID' => $guid],
        ]);
        if ($group = $obGroupTable->fetch()) {
            return $group;
        }
    }

    public static function getUsersInGroup($id)
    {
        $obUserGroupTable = \Bitrix\Main\UserGroupTable::Getlist([
            'filter' => ['GROUP_ID' => $id],
        ]);
        while ($user = $obUserGroupTable->fetch()) {
            $users[] = $user;
        }
        return $users;
    }

    public static function getUserManagers($userID)
    {
        if (!\ALG\User::IsMainUser($userID)) {
            $userID = \ALG\User::getMainUserId($userID);
        }
        if (!$managersGroupsID = self::getUserField('UF_MANAGERS_GROUPS', $userID)) {
            return null;
        }

        $arFilter = ['IBLOCK_ID' => \WC\IBlock\Tools::getIBlockIDByCode('MANAGERS_GROUPS'), 'ID' => $managersGroupsID];
        $arSelect = ['IBLOCK_ID', 'ID', 'PROPERTY_MANAGER'];
        $res = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        while ($ar = $res->GetNext()) {
            $entity = HighloadBlockTable::compileEntity('Polzovateli');
            $entityDataClass = $entity->getDataClass();
            $res2 = $entityDataClass::getList([
                'select' => ['UF_NAME', 'UF_MANAGER_EMAIL'],
                'filter' => ['UF_XML_ID' => $ar['PROPERTY_MANAGER_VALUE']],
            ]);
            if ($ar2 = $res2->Fetch()) {
                if (check_email($ar2['UF_MANAGER_EMAIL'])) {
                    $email = $ar2['UF_MANAGER_EMAIL'];
                }
                $managers[] = ['NAME' => $ar2['UF_NAME'], 'EMAIL' => $email];
            }
        }
        return $managers;
    }

    public static function getStoreOperators($storeID)
    {
        $obStore = \CCatalogStore::GetList([], ['ID' => $storeID], false, false, ['UF_STORE_MANAGER']);
        if ($arStore = $obStore->fetch()) {
            $entity = HighloadBlockTable::compileEntity('Polzovateli');
            $entityDataClass = $entity->getDataClass();
            $res = $entityDataClass::getList([
                'select' => ['UF_MANAGER_EMAIL'],
                'filter' => ['ID' => $arStore['UF_STORE_MANAGER']],
            ]);
            if ($ar = $res->Fetch()) {
                if (check_email($ar['UF_MANAGER_EMAIL'])) {
                    $email = $ar['UF_MANAGER_EMAIL'];
                }
                $operators[] = ['NAME' => $ar['UF_NAME'], 'EMAIL' => $email];
            }
        }
        return $operators;
    }

    public static function getEmailsFromArray($array)
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
        $user = new \CUser();
        $userId = $userId ?: $user->GetID();

        if (($userInfo = $user::GetByID($userId)->Fetch()) && $userInfo[$field]) {
            return $userInfo[$field];
        }

        return null;
    }

    public static function getSiteId()
    {
        if (!$siteId = \Bitrix\Main\Context::getCurrent()->getSite()) {
            $arSiteTable = \Bitrix\Main\SiteTable::getList([
                'select' => ['LID'],
            ])->fetch();
            $siteId = $arSiteTable['LID'];
        }
        return $siteId;
    }

    public static function reformatArrayKeys($array, $toType = null)
    {
        foreach ($array as $key => $value) {
            $key = self::reformatString($key, $toType);
            if ($value && is_object($value)) {
                $value = Json::encode($value);
                $value = Json::decode($value);
            }
            if ($value && is_array($value)) {
                $value = self::reformatArrayKeys($value, $toType);
            }
            $return[$key] = $value;
        }
        return $return;
    }

    private static function reformatString($string, $toType)
    {
        switch ($toType) {
            case 'toSnake':
                // camelCase to SNAKE_CASE
                $arString = preg_split('/(?=[A-Z])/', $string);
                if (count($arString) > 1) {
                    $string = implode('_', $arString);
                }
                $string = strtoupper($string);
                break;
            default:
                // SNAKE_CASE to camelCase
                if (strpos($string, '_') === false && ctype_upper($string) === false) {
                    break;
                }
                $string = strtolower($string);
                $arString = explode('_', $string);
                if (count($arString) > 1) {
                    foreach ($arString as $key => $value) {
                        if ($key > 0) {
                            $arString[$key] = ucfirst($value);
                        }
                    }
                    $string = implode('', $arString);
                }
        }

        return $string;
    }
}