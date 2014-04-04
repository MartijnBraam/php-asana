<?php
/**
 * Created by PhpStorm.
 * User: martijn
 * Date: 4/4/14
 * Time: 8:17 PM
 */

//This files sets $apikey
require_once(__DIR__ . '/apikey.php');
require_once(__DIR__ . '/../vendor/autoload.php');

$asana = new \PhpAsana\Asana();
$asana->loginApiKey($apikey);
$workspaces = $asana->getWorkspaces();

$testprojects = $workspaces['Test']->getProjects();
echo $testprojects['Test projectje']->notes;
var_dump($testprojects);