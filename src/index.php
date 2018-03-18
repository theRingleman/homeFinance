<?php

require 'vendor/autoload.php';

$f3 = \Base::instance();

// Add our config
$f3->config('app/config.ini');
// Add routes
$f3->config('app/routes.ini');
// Set up our database creds and set DB as an f3 global.
$dbCreds = json_decode(file_get_contents('creds.json'));
$f3->set('DB', new DB\SQL(
    'mysql:host=homestorage;port=3306;dbname=homecore',
    "{$dbCreds->database->username}",
    "{$dbCreds->database->password}"
));

//$f3->set('ONERROR', 'Controller->renderError');

$f3->run();