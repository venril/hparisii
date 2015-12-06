<?php

// APPLICATION
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PATH', dirname(__DIR__));
define('LIB_PATH', ROOT_PATH . DS . 'library');
define('APP_PATH', ROOT_PATH . DS . 'app');

// DB
define('DB_DRIVER', 'mysql');
define('DB_HOST', 'localhost');
define('DB_NAME', 'blogger');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'admin');
define('DB_PORT', 3306);
define('DB_ENCODING', 'UTF8');

// VIEW
define('VIEW_PATH', APP_PATH . DS . 'views');
define('VIEW_DEFAULT_LAYOUT', 'layout.phtml');
