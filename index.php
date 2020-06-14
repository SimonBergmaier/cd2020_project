<?php
require_once('inc/bootstrap.php');
require('vendor/autoload.php');

$default_view = 'list';
$view = $default_view;

if (
	isset($_REQUEST['view']) &&
	file_exists(__DIR__ . '/views/' . $_REQUEST['view'] . '.php')
) {
	$view = $_REQUEST['view'];
}

$postAction = isset($_REQUEST[\Blog\Controller::ACTION]) ? $_REQUEST[\Blog\Controller::ACTION] : null;

if($postAction) {
    \Blog\Controller::getInstance()->invokePostAction();
}

require_once ('views/' . $view . '.php');



