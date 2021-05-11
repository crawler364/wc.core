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

    public static function getCommonLangFile(): string
    {
        return static::getModulePath() . '/common.php';
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
