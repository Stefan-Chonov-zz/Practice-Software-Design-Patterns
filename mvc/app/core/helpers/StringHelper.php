<?php

namespace Core\Helpers;

class StringHelper
{
    /**
     * @param $string
     * @return bool
     */
    public static function isEmpty($string)
    {
        $string = trim($string);
        if (!isset($string) || empty($string)) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * @param $string
     * @return bool
     */
    public static function hasOnlyLetters($string)
    {
        $hasSpecialChars = preg_match('/[#$%!^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/', $string);
        $hasDigits = preg_match('/\\d/', $string) > 0;

        return !$hasSpecialChars && !$hasDigits;
    }

    /**
     * @param $string
     * @return bool
     */
    public static function hasOnlyDigits($string)
    {
        return ctype_digit($string);
    }

    /**
     * @param $string
     * @return mixed
     */
    public static function isEmail($string)
    {
        return filter_var($string, FILTER_VALIDATE_EMAIL);
    }
}