<?php


namespace WC\Main;


use Bitrix\Main\Localization\Loc;

class Messages
{
    // Подключать общий и дополнительный lang файлы
    public function __construct(string $fileExt = null)
    {
        Loc::loadMessages(__FILE__);

        if ($fileExt) {
            Loc::loadMessages($fileExt);
        }
    }

    // Чтобы не указывать каждый раз CODE
    final public function get($code, $replace = null, $language = null)
    {
        return ['MESSAGE' => Loc::getMessage($code, $replace, $language), 'CODE' => $code];
    }
}