<?php


namespace WC\Core\Helpers;


use Bitrix\Main\Web\Json;

class ReformatArray
{
    /** @var array $params = ['CASE' => 'camel2snake' | 'snake2camel', 'TO_STRING' => true | false] */
    public static $params = [];

    public static function handler(array $array): array
    {
        foreach ($array as $key => $value) {
            $key = self::reformatString($key);

            if ($value && is_object($value)) {
                $value = Json::encode($value);
                $value = Json::decode($value);
            }
            if ($value && is_array($value)) {
                $value = self::handler($value);
            }
            if ($value && is_numeric($value) && self::$params['TO_STRING'] !== false) {
                $value = (string)$value;
            }

            $return[$key] = $value;
        }

        return $return;
    }

    public static function reformatString($string): string
    {
        switch (self::$params['CASE']) {
            case 'camel2snake':
                $arString = preg_split('/(?=[A-Z])/', $string);
                if (count($arString) > 1) {
                    $string = implode('_', $arString);
                }
                $string = strtoupper($string);
                break;
            case 'snake2camel':
            default:
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
