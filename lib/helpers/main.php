<?php


namespace WC\Core\Helpers;


use Bitrix\Main\Web\Json;

class Main
{
    public static function showBreadCrumbs($APPLICATION): bool
    {
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
        $obGroupTable = \Bitrix\Main\GroupTable::Getlist([
            'filter' => ['STRING_ID' => $guid],
        ]);
        if ($group = $obGroupTable->fetch()) {
            return $group;
        }

        return null;
    }

    public static function getUsersInGroup($id): array
    {
        $obUserGroupTable = \Bitrix\Main\UserGroupTable::Getlist([
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

    public static function getUserField($field, $userId = null, $enum = false)
    {
        $user = new \CUser();
        $userId = $userId ?: $user->GetID();

        if (($userInfo = $user::GetByID($userId)->Fetch()) && $userInfo[$field]) {
            if ($enum) {
                $cUserFieldEnum = new \CUserFieldEnum();
                $dbRes = $cUserFieldEnum->GetList([], ['ID' => $userInfo[$field]]);
                if ($field = $dbRes->GetNext()) {
                    return $field;
                }
            } else {
                return $userInfo[$field];
            }
        }

        return null;
    }

    public static function getSiteId(): string
    {
        if (!$siteId = \Bitrix\Main\Context::getCurrent()->getSite()) {
            $siteId = \Bitrix\Main\SiteTable::getList(['select' => ['LID']])->fetch()['LID'];
        }

        return $siteId;
    }

    // todo это в wc.core handlers и придумать как для json все в строку
    public static function reformatArrayKeys($array, $toType = null): array
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

    private static function reformatString($string, $toType): string
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
