<?php

global $project;
$project = 'mysite';

global $databaseConfig;
$databaseConfig = array(
	'type' => 'MySQLDatabase',
	'server' => 'localhost',
	'username' => 'SSCC',
	'password' => 'bUtb2U2RerLm3xTE',
	'database' => 'SSCC',
	'path' => ''
);

// Set the site locale
i18n::set_locale('en_AU');
