<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 23.03.2018
 * Time: 10:53
 */

namespace Blog;


class BaseObject
{
    public function __call(string $name, array $arguments)
    {
        throw new \Exception("Method ".$name." is not declared");
    }

    public function __get(string $name) {
        throw new \Exception("Attribute ". $name . " is not declared");
    }

    public function __set(string $name, $value) {
        throw new \Exception("Attribute ". $name . " is not declared");
    }

    public static function __callStatic(string $name, array $arguments) {
        throw new \Exception("Static Method ". $name . " is not declared");
    }
}