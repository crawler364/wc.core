<?php


namespace WC\Main\Localization;


use Bitrix\Main\Context;

IncludeModuleLangFile(__FILE__);

class Loc extends \Bitrix\Main\Localization\Loc
{
    protected static $currentLang;

    final public static function getMessageExt($code, $replace = null, $language = null): array
    {
        if ($language === null) {
            if (static::$currentLang === null) {
                self::getCurrentLang();
            }

            $language = static::$currentLang;
        }

        // Подготовка Loc::getMessage для result->addError
        return ['MESSAGE' => parent::getMessage($code, $replace, $language), 'CODE' => $code];
    }

    public static function getCurrentLang(): ?string
    {
        if (self::$currentLang === null) {
            $context = Context::getCurrent();
            if ($context !== null) {
                self::$currentLang = $context->getLanguage();
            } else {
                self::$currentLang = 'en';
            }
        }
        return self::$currentLang;
    }
}