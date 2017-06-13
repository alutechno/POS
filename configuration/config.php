<?php
/*
| -------------------------------------------------------------------
| MASTER CONFIGURATION
| -------------------------------------------------------------------
*/
error_reporting(E_ALL);
ini_set('display_errors', 1);

// -- App Info
define('APP_TITLE'  , 'POS- The Media Hotel and Towers');
define('AUTHOR', 'AHMAD ISMAIL - 0817212162');
define('APP_NAME'   , 'POS'); //no space please
define('APP_CORP'   , 'THE MEDIA HOTEL AND TOWERS');
define('APP_VERSION', '0.1');
define('APP_YEAR'   , '2017');
define('LICENSE_TO' , 'THE MEDIA HOTEL AND TOWERS');
define('REPORT_BIRT','http://localhost:8080/birt/frameset?__report=report/pos/');
define('BIRT','http://localhost:8080/birt/output?__report=report/pos/struk_order.rptdesign&__format=pdf');
define('BIRT_CLOSE_CASHIER','http://localhost:8080/birt/output?__report=report/pos/close_cashier.rptdesign&dpi=96&__format=pdf&pageoverflow=0&_overwrite=false&transc_id=');
// -- Module
define('DEF_MODULE'     , 1); // 1. perencanaan 2.etc  ref => apps table
define('SELECT_MODULE'  , TRUE);


define('MY_ENV', 'production'); //development testing production



$db_debug =  (MY_ENV == 'development') ? TRUE : FALSE;
define('DB_DBUG', $db_debug);
define('DB_TYPE', 'mysqli');  //mysql postgre
//define('DB_HOST', '103.43.47.115');
define('DB_HOST', '10.108.220.3');
define('DB_PORT', '3306');
define('DB_USER', 'media');
define('DB_PASS', 'media');
define('DB_NAME', 'media');

// -- Url
$PROTOCOL = "http" . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "s" : "") . "://";
$SERVER   = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
$SERVER   = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : $SERVER;
$BASE_URL = $PROTOCOL . $SERVER . str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
define('MY_BASE_URL', $BASE_URL);
define('MY_ASSETS', $BASE_URL.'themes/');
define('MY_ASSETS2', $BASE_URL.'themes2/');
define('VIRTUAL_KEYBOARD', $BASE_URL.'keyboard/');
define('PRINT_BIRT', $BASE_URL.'Print/');



define('MY_INDEX_PAGE', '');

// -- Hook
define('MY_ENABLE_HOOKS', FALSE);

// -- Compress Output
define('MY_COMPRESS_OUTPUT', FALSE);

// -- Cache n minutes
define('MY_CACHE', 0);
define('MY_CACHE_PATH', 'cache/');

// -- Error Logging Threshold 0-4
$err_log = (MY_ENV == 'development') ? 4 : 0;
define('MY_LOG_THRESHOLD', $err_log);

// -- Encrypt & Security
define('MY_ENCRYPTION_KEY', 'mr34n1k');
define('MY_GLOBAL_XSS_FILTERING', TRUE);
define('MY_CSRF_PROTECTION', FALSE);
define('MY_CSRF_TOKEN_NAME', APP_NAME.'_csrf_test');
define('MY_CSRF_COOKIE_NAME', APP_NAME.'_cookie_name');
define('MY_CSRF_EXPIRE', 150);
define('MY_SESS_COOKIE_NAME', APP_NAME.'_session');
define('MY_SESS_TABLE_NAME', APP_NAME.'_session');

// -- Etc
define('ADMIN_NAME', 'Administrator');
define('ADMIN_EMAIL', 'asd@ajetjet.com');
define('ADMIN_DATE_FORMAT', '%D, %d %M %Y %H:%i');
define('ADMIN_DATE_TIME_FORMAT', 'd/m/y H:i');
define('EMAIL_POSTF', '@ajetjet.com');
define('LOGIN_ATTEMPT', 3);
define('LOGIN_ATTEMPT_EXPIRE', 20); //60*60*24);

?>
