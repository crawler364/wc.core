<?php


namespace WC\Core\Handlers;


class Mail
{
    // todo в админку
    private static $bccEmail = '';
    private static $testDomain = '';

    // crawler364 Установить доп. параметр -f отправитель письма (Return-path, envelope-from..) из заголовка From
    static function custom_mail($to, $subject, $message, $additional_headers, $additional_parameters): bool
    {
        if (self::$bccEmail){
            $additional_headers .= "\r\n" . "BCC: " . self::$bccEmail;
        }

        $template = '/From:.*?<?\b([a-z0-9._-]+@[a-z0-9.-]+)\b/i';
        preg_match_all($template, $additional_headers, $matches);

        if ($_SERVER['SERVER_NAME'] !== self::$testDomain) {
            $from = '-f' . filter_var(trim($matches[1][0]), FILTER_VALIDATE_EMAIL);
        }
        if ($additional_parameters !== '') {
            return @mail($to, $subject, $message, $additional_headers, $additional_parameters);
        }
        if ($from) {
            return @mail($to, $subject, $message, $additional_headers, $from);
        }

        return @mail($to, $subject, $message, $additional_headers);
    }
}
