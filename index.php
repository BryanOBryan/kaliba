<?php

/**
 * Define the application's minimum supported PHP version as a constant so it can be referenced within the application.
 */
define('KALIBA_PHP_VERSION', '7.0.0');

if (version_compare(PHP_VERSION, KALIBA_PHP_VERSION, '<'))
{
    die('Your host needs to use PHP ' . KALIBA_PHP_VERSION . ' or higher to run this Application');
}

/**
 * =============================================================================
 * DEFINE FILE SIZES
 * =============================================================================
 */
define('FILE_SIZE_KB', 1024);
define('FILE_SIZE_MB', 1048576);
define('FILE_SIZE_GB', 1073741824);

/**
 * =============================================================================
 * DEFINE APPLICATION PATHS
 * =============================================================================
 */

define('PATH_BASE', __DIR__);
define('DS', DIRECTORY_SEPARATOR);
$parts = explode(DS, PATH_BASE);
define('PATH_ROOT',         implode(DS, $parts));
define('PATH_APP',          PATH_ROOT . DS . 'app' );
define('PATH_CONFIG',       PATH_ROOT . DS . 'config');
define('PATH_STORAGE',      PATH_ROOT . DS . 'storage');
define('PATH_RESOURCE',     PATH_ROOT . DS . 'resource');
define('PATH_CACHE',        PATH_STORAGE . DS . 'cache');
define('PATH_DATABASE',     PATH_STORAGE . DS . 'database');
define('PATH_COMPILED',     PATH_STORAGE . DS . 'compiled');
define('PATH_LOGS',         PATH_STORAGE . DS . 'logs');
define('PATH_SESSION',      PATH_STORAGE . DS . 'session');
define('PATH_LANGUAGE',     PATH_RESOURCE . DS . 'languages');
define('PATH_TEMPLATE',     PATH_RESOURCE . DS . 'templates');
define('PATH_ASSETS',               'resource/assets/' );
define('PATH_CONTROLLER',           'App/Controllers' );
define('PATH_VIEW',                 'App/Views' );
define('PATH_UPLOADS',              'uploads/' );

/**
 * =============================================================================
 * LOAD SYSTEM LIBRARY
 * =============================================================================
 */

require (PATH_ROOT.DS.'vendor/autoload.php');
require (PATH_ROOT.DS.'include/helpers.php');

use Kaliba\Configure\Config;
use Kaliba\Logging\FileLogger;
use Kaliba\Logging\Log;
use Kaliba\Error\ErrorHandler;
use Kaliba\Error\ExceptionHandler;
use Kaliba\Support\Options;
use Kaliba\Database\DbManager;
use Kaliba\Http\Request;
use Kaliba\Routing\FrontController;
use Kaliba\Templating\Template;
use Kaliba\Mail\MailManager;

/**
 * =============================================================================
 * LOAD CONFIGURATIONS
 * =============================================================================
 */

$config = new Config(PATH_CONFIG);
$config->setAsGlobal();

/**
 * =============================================================================
 * REGISTER APPLICATION LOGGER
 * =============================================================================
 */

$loggerOptions = new Options();
$loggerOptions->set('storage', PATH_LOGS);
$logger = new FileLogger($loggerOptions);
Log::register($logger); 

/**
 * =============================================================================
 * REGISTER CUSTOM ERROR HANDLER
 * =============================================================================
 */

$errorOptions = new Options($config->get('error'));
$errhandler = new ErrorHandler($errorOptions); 
error_reporting($errorOptions->get('level'));
set_error_handler([$errhandler, 'handle'], $errorOptions->get('level'));
register_shutdown_function([$errhandler, 'shutDown']);
$exchandler = new ExceptionHandler($errorOptions);
set_exception_handler([$exchandler, 'handle']);

/**
 * =============================================================================
 * REGISTER DATABASE CONNECTION
 * =============================================================================
 */

$dbManager = new DbManager();
$dbManager->configure($config->get('database'));
$dbManager->setAsGlobal();

/**
 * =============================================================================
 * REGISTER MAILER
 * =============================================================================
 */

$mailer = new MailManager();
$mailer->configure($config->get('mail'));
$mailer->setAsGlobal();

/**
 * =============================================================================
 * CONFIGURE TEMPLATE SYSTEM
 * =============================================================================
 */

$template = new Template(PATH_TEMPLATE, PATH_COMPILED);
$template->setFileExtension('.html');
$template->setBaseUrl(base_url());
$template->setAsGlobal();

/**
 * =============================================================================
 * RUN THE APPLICATION
 * =============================================================================
 */

$fc = new FrontController();
$fc->middleware($config->get('middleware')); 
$fc->controllerPath(PATH_CONTROLLER);
$fc->viewPath(PATH_VIEW);
$fc->execute(new Request());

