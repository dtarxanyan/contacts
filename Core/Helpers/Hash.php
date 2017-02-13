<?php
namespace Core\Helpers;


class Hash
{
    /**
     * @param $value
     * @return bool|string
     */
    public static function bcrypt($value)
    {
        return password_hash($value, PASSWORD_BCRYPT);
    }

    /**
     * @param $value
     * @param $hash
     * @return bool
     */
    public static function compare($value, $hash)
    {
        return password_verify($value, $hash);
    }
}