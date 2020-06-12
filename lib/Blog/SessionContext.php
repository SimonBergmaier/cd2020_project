<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 13.04.2018
 * Time: 09:30
 */

namespace Blog;


class SessionContext extends BaseObject
{
    private static $exists = false;

    public static function create() : bool {
        if(!self::$exists) {
            self::$exists = session_start();
        }
        return self::$exists;
    }
}