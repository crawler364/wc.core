<?php


namespace WC\Core\Ui\Helpers;


class Attributes
{
    public static function stringify($attributes): string
    {
        if (is_array($attributes)) {
            $htmlAttributes = [];

            foreach ($attributes as $key => $value) {
                if (is_numeric($key)) {
                    $htmlAttributes[] = $value;
                } else if ($value === false || $value === null) {
                    continue;
                } else if ($value === true || (string)$value === '') {
                    $htmlAttributes[] = htmlspecialcharsbx($key);
                } else {
                    $htmlAttributes[] = htmlspecialcharsbx($key) . '="' . htmlspecialcharsbx($value) . '"';
                }
            }

            $result = implode(' ', $htmlAttributes);
        } else {
            $result = (string)$attributes;
        }

        return $result;
    }
}
