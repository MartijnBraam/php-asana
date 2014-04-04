php-asana
=========

This is an object orientated module for using the [Asana](http://asana.com/) api. 

Installation
------------
Clone the git repository
```
$ git clone git@github.com:MartijnBraam/php-asana.git
```
Install composer dependencies
```
$ cd php-asana
$ composer install
```

Usage
-----

Connecting to asana with an api key
```php
$asana = new \PhpAsana\Asana();
$asana->loginApiKey('secret');
```
Retrieving workspaces and projects in your account
```php
$workspaces = $asana->getWorkspaces();
$testprojects = $workspaces['Test']->getProjects(); //get id and name for all projects in workspace

```
Changing project properties
```php
$testprojects['Test project']->notes = 'This is the new description for the Test project';
$testprojects['Test project']->save(); //upload properties to asana
```
