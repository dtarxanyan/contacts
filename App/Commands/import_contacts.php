<?php
namespace App\Commands;
use App\Models\Contact;
use \Core\Application;

DEFINE("BASE_PATH", 'C:/xampp/htdocs');

require_once BASE_PATH . "/contacts/vendor/autoload.php";

$application = new Application();
$application->start();

$csvFilePaht = $argv[1];
$userId = $argv[2];
$processId = $argv[3];

$model = new Contact();
$model->importCsv($csvFilePaht, $userId, $processId);