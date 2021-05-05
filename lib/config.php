<?php

namespace WC\Core;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

class Config
{
    public static function getModuleName(): string
    {
        return 'wc.core';
    }

    public static function getLang($code, $replaces = null, $fallback = null): ?string
    {
        $prefix = static::getLangPrefix();

        $result = Loc::getMessage($prefix . $code, $replaces) ?: $fallback;

        if ($result === null) {
            $result = $code;
        }

        return $result;
    }

    public static function getLangPrefix(): string
    {
        return 'WC_CORE_';
    }

    public static function getNamespace(): string
    {
        return '\\' . __NAMESPACE__;
    }

    public static function getModulePath(): string
    {
        return __DIR__;
    }

    public static function getOption($name, $default = "", $siteId = false): ?string
    {
        $moduleName = static::getModuleName();

        return Option::get($moduleName, $name, $default, $siteId);
    }

    public static function setOption($name, $value = "", $siteId = ""): void
    {
        $moduleName = static::getModuleName();

        Option::set($moduleName, $name, $value, $siteId);
    }

    public static function removeOption($name = null): void
    {
        $moduleName = static::getModuleName();
        $filter = $name ? ['name' => $name] : [];

        Option::delete($moduleName, $filter);
    }
}
