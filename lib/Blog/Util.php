<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 13.04.2018
 * Time: 09:17
 */

namespace Blog;


class Util extends BaseObject
{
    public static function escape(string $string) : string {
       return nl2br(htmlspecialchars($string));
    }

    public static function action(string $action, array $params = null) : string {

        $page = isset($_REQUEST[Controller::PAGE]) ? $_REQUEST[Controller::PAGE] : $_SERVER['REQUEST_URI'];

        $res = "index.php?" . Controller::ACTION . "=" . rawurlencode($action) . "&" . Controller::PAGE . '=' . rawurlencode($page);

        if(is_array($params)) {
            foreach ($params AS $name => $value) {
                $res .= "&" . rawurlencode($name) . "=" . rawurlencode($value);
            }
        }

        return $res;
    }

    public static function redirect(string $page = null) {
        if($page==null) {
            $page = isset($_REQUEST[Controller::PAGE]) ? $_REQUEST[Controller::PAGE] : $_SERVER['REQUEST_URI'];
        }

        header('Location:'.$page);
        exit();
    }
}