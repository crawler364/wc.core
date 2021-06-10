<?php


namespace WC\Core\Handlers\Internals;


abstract class UserBase
{
    public static function add(array $fields): \CUser
    {
        $user = new \CUser();
        $user->Add($fields);

        return $user;
    }

    abstract public static function autoRegister(array $fields);
}
