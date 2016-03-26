<?php
/**
| Created by: Filipe Mota de Sá - pihh.rocks@gmail.com
| Date: 26/03/2016
| Time: 22:21
| Requirements:
|  .env file
 */

/**
|---------------------------
| Error display and Report
|---------------------------
 */

ini_set('display_errors', 0);
error_reporting(0);

/**
|---------------------------
| Base paths
|---------------------------
 */

$framework_root = (trim(trim(str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__),'\\'),'/'));
(strlen($framework_root) >0 ) ? $framework_root = '/'.$framework_root.'/' : $framework_root = '/';

define('DS', DIRECTORY_SEPARATOR);
define('BASE_URL','https://github.com/pihh/');
define('BASE_FOLDER',BASE_URL.'');
define('FRAMEWORK_ROOT',$framework_root);
define('ROOT', $_SERVER['DOCUMENT_ROOT']);

/**
|---------------------------
| Environment variables
|---------------------------
 */

define('ENV_SETUP', 0);
define('ENV_PATH',ROOT.DS.'.env');

/**
|---------------------------
| Language
|---------------------------
 */
define('DEFAULT_LANG','pt_PT');
const ALLOWED_LANGS = array(
    'pt_PT',
    'pt_BR',
    'en_EN'
);


/**
|---------------------------
| Live and Dev Specific details
|---------------------------
 */
define('MODE',getenv('mode'));

if(MODE == 'dev'){

    define('BASE_URL' , 'http://localhost:314');
    define('BASE_FOLDER' , BASE_URL);

    # SET ERROR REPORTING
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    # set debug and log
    ini_set('log_errors', 1);
    $log_path = ROOT.DS.'app'.DS.'files'.DS.'logs'.DS.'mariana_'.date('Y-m-d');

    if(!is_dir($log_path)){
        mkdir($log_path,0777);
    }

    ini_set('error_log', $log_path.DS.'error.log');
}

/**
|---------------------------
| Database settings
|---------------------------
 */

define('DATABASE', array(
    'host' => $_ENV['DB_HOST'],
    'database' => $_ENV['DB_DATABASE'],
    'driver'   => 'mysql',// mysql or SQLite3
    'username' => $_ENV['DB_USERNAME'],
    'password' => $_ENV['DB_PASSWORD'],
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'engine'    =>  'InnoDB',
    'prefix' => ''
));

/**
|---------------------------
| Session settings
|---------------------------
 */
define('SESSION',array(
    'https' => true,
    'user_agent' =>  true,
    'lifetime'  =>  7200, //seconds
    'cookie_lifetime' => 0, //[(0:Clear the session cookies on browser close)
    'refresh_session' =>  600, //regenerate Session Id
    'table'         =>'sessions',
    'salt'          => 'salt'
));

/**
|---------------------------
| Email settings
|---------------------------
 */
define('EMAIL', array(
    'smtp-server'   =>  $_ENV['MAIL_HOST'],
    'port'          =>  $_ENV['MAIL_PORT'],
    'timeout'       =>  '30',
    'email-login'   =>  $_ENV['MAIL_USERNAME'],
    'email-password'=>  $_ENV['MAIL_PASSWORD'],
    'email-replyTo' =>  'pihh.rocks@gmail.com',
    'website'       =>  Config::get('website'),
    'charset'       =>  'windows-1251'
));

/**
|---------------------------
| Cache settings
|---------------------------
 */

define('CACHE_TIMEOUT', 60);

/**
|---------------------------
| Encription Settings
|---------------------------
 */
define('HASH', getenv('key'));
define('SECURITY_REPORT_EMAIL', getenv('SECURITY_REPORT_MAIL'));
