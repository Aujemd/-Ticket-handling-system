<?php

require_once '../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Aura\Router\RouterContainer;

session_start();//Inicializa la sesión

if(file_exists("../.env")){
    $dotenv = Dotenv\Dotenv::createMutable(__DIR__ . '/..');
    $dotenv->load();
}

if(getenv('DEBUG') == 'true'){
    ini_set('display_errors', 1);
    ini_set('display_starup_error', 1);
    error_reporting(E_ALL);
}

var_dump(getenv('DB_DRIVER'));
var_dump(getenv('DB_HOST'));
var_dump(getenv('DB_NAME'));
var_dump(getenv('DB_USER'));
var_dump(getenv('DB_PASS'));
$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => getenv('DB_DRIVER'),
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
$map->get('dashboardAdmin', getenv('BASE_URL').'Dashboard/User', [
    'controller' => 'App\Controllers\DashboardController',
    'action' => 'getDashboardUserAction',
]);
$map->get('dashboardUser', getenv('BASE_URL').'Dashboard/Admin', [
    'controller' => 'App\Controllers\DashboardController',
    'action' => 'getDashboardAdminAction',
]);
$map->get('logout', getenv('BASE_URL').'Logout',[
    'controller' => 'App\Controllers\UsersController',
    'action' => 'getUsersLogoutAction',
]);
$map->get('showTicket', getenv('BASE_URL').'Show/Ticket',[
    'controller' => 'App\Controllers\TicketsController',
    'action' => 'getTicketsAction',
]);
$map->get('editTicket', getenv('BASE_URL').'Edit/Ticket',[
    'controller' => 'App\Controllers\TicketsController',
    'action' => 'getTicketsAction',
]);
$map->get('deleteTicket', getenv('BASE_URL').'Delete/Ticket',[
    'controller' => 'App\Controllers\TicketsController',
    'action' => 'getTicketsAction',
]);
$map->get('createEvent', getenv('BASE_URL').'Event/Create',[
    'controller' => 'App\Controllers\AdminController',
    'action' => 'getCreateEventAction',
]);
$map->post('saveEvent', getenv('BASE_URL').'Event/Create',[
    'controller' => 'App\Controllers\AdminController',
    'action' => 'getSaveEventAction',
]);
$map->post('saveUsers', getenv('BASE_URL').'SignUp',[
    'controller' => 'App\Controllers\UsersController',
    'action' => 'getUsersSignUpAction',
 ]);
 $map->post('auth', getenv('BASE_URL').'Login',[
    'controller' => 'App\Controllers\UsersController',
    'action' => 'getUsersLoginAction',
]);
$map->post('saveTicket', getenv('BASE_URL').'TicketRegistry',[
    'controller' => 'App\Controllers\TicketRegistryController',
    'action' => 'getTicketRegistryAction',
]);
$map->post('updateTicket', getenv('BASE_URL').'Update/Ticket',[
    'controller' => 'App\Controllers\TicketsController',
    'action' => 'getTicketsAction',
]);
$map->post('chooseEvent', getenv('BASE_URL').'Event/Choose',[
    'controller' => 'App\Controllers\DashboardController',
    'action' => 'getDashboardUserAction',
]);

$matcher = $routerContainer->getMatcher();

$route = $matcher->match($request);

if(!$route){
    echo 'No Route';
    var_dump( $request->getUri()->getPath());
    var_dump(getenv('BASE_URL'));
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