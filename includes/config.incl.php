<?php
/*
* Site configuration file for front-end (non-admin) 
* of unicenta webfrontend.
*/

//Database connection
define('DB', '/var/unicenta-connect.incl.php');

define('BASE_URI', '/var/www/Dropbox/unicenta-web-frontend/');
define('WEB_ROOT', '/Dropbox/unicenta-web-frontend/');
define('BASE_URL', 'http://localhost/Dropbox/unicenta-web-frontend/');
define('SITE_NAME', 'Unicenta Web Frontend');
define('TEMPLATE_BASE_URI', BASE_URI . 'templates/default/');
define('TEMPLATE_BASE_URL', BASE_URL . 'templates/default/');
define('TEMPLATE_INIT', TEMPLATE_BASE_URI . 'init.incl.php');

