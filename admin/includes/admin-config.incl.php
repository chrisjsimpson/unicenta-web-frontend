<?php

//Config file for unicenta web frontend
define('ADMIN_BASE_URL', 'http://localhost/Dropbox/unicenta-web-frontend/admin/');

//Define DB connection if not already defined by site-wide config.
if(!DB)
{
	define('DB', '../../unicenta.mysqlconnect.incl.php');
}

require_once(DB);
