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

$users = $asana->getUsers();
var_dump($users);