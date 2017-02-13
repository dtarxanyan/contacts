<?php
use \Core\Application;

DEFINE("BASE_PATH", __DIR__);
DEFINE("UPLOAD_PATH", BASE_PATH . '/App/Uploads/');

require_once __DIR__ . "/vendor/autoload.php";
$application = new Application();
$application->start();
