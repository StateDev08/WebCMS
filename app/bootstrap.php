<?php

require __DIR__.'/core/Helpers.php';
require __DIR__.'/core/Env.php';
require __DIR__.'/core/Db.php';
require __DIR__.'/core/Router.php';
require __DIR__.'/core/View.php';
require __DIR__.'/core/Auth.php';
require __DIR__.'/core/Csrf.php';
require __DIR__.'/core/Request.php';

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (! str_starts_with($class, $prefix)) {
        return;
    }
    $relative = substr($class, strlen($prefix));
    $path = __DIR__.'/'.str_replace('\\', '/', $relative).'.php';
    if (file_exists($path)) {
        require $path;
    }
});

App\Core\Env::load(__DIR__.'/../.env');
$config = require __DIR__.'/config.php';

date_default_timezone_set($config['app']['timezone']);
ini_set('display_errors', $config['app']['debug'] ? '1' : '0');
error_reporting($config['app']['debug'] ? E_ALL : 0);

$sessionPath = __DIR__.'/../storage/sessions';
if (! is_dir($sessionPath)) {
    mkdir($sessionPath, 0777, true);
}
ini_set('session.save_path', $sessionPath);
session_start();

App\Core\Db::init($config['db']);
App\Core\Auth::init();
App\Core\View::setBasePath(__DIR__.'/../views');
App\Core\View::share('app', $config['app']);

$router = new App\Core\Router();
require __DIR__.'/routes.php';

return $router;
