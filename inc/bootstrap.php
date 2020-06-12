<?php

declare(strict_types=1);
error_reporting(E_ALL);
ini_set("display_errors", "1");


spl_autoload_register(function($class) {
   $filename = __DIR__."/../lib/".str_replace("\\",DIRECTORY_SEPARATOR, $class).".php";

   if(file_exists($filename)) {
       require_once($filename);
   }

});

\Blog\SessionContext::create();

//require_once (__DIR__."/../lib/Data/DataManager_mock.php");
require_once (__DIR__."/../lib/Data/DataManager_mysql.php");