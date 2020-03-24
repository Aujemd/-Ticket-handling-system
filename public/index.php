<?php

require_once '../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Aura\Router\RouterContainer;

session_start();//Inicializa la sesión

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/..');
$dotenv->load(); 

if(getenv('DEBUG') == 'true'){
    ini_set('display_errors', 1);
    ini_set('display_starup_error', 1);
    error_reporting(E_ALL);
}

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => getenv('DB_HOST'),
    'database'  => getenv('DB_NAME'),
    'username'  => getenv('DB_USER'),
    'password'  => getenv('DB_PASS'),
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

$routerContainer = new RouterContainer();

$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

$map = $routerContainer->getMap();

$map->get('index', getenv('BASE_URL'), [
    'controller' => 'App\Controllers\IndexController',
    'action' => 'indexAction',
]);
$map->get('SignUp', getenv('BASE_URL').'SignUp', [
    'controller' => 'App\Controllers\UsersController',
    'action' => 'getUsersAction',
]);
$map->get('dashboard', getenv('BASE_URL').'Dashboard', [
    'controller' => 'App\Controllers\DashboardController',
    'action' => 'getDashboardAction',
]);
$map->get('login', getenv('BASE_URL').'Login', [
    'controller' => 'App\Controllers\AuthController',
    'action' => 'getLoginAction',
]);
$map->post('saveUsers', getenv('BASE_URL').'SignUp',[
    'controller' => 'App\Controllers\UsersController',
    'action' => 'getUsersAction',
 ]);
 $map->post('auth', getenv('BASE_URL').'Login',[
    'controller' => 'App\Controllers\AuthController',
    'action' => 'getLoginAction',
]);

$matcher = $routerContainer->getMatcher();

$route = $matcher->match($request);

if(!$route){
    echo 'No Route';
}else{
    $handlerData = $route->handler;
    $controllerName = $handlerData['controller'];
    $actionName = $handlerData['action'];
    $controller = new $controllerName;
    $response = $controller->$actionName($request);

    foreach($response->getHeaders() as $name => $values){//Mojon para que no se puteen las url al redireccionar las paginas
        foreach($values as $value){
            header(sprintf('%s: %s', $name, $value), false);
        }
    }

    http_response_code($response->getStatusCode());
    echo $response->getBody();
}