<?php
define("DIR", __DIR__.'/');

require "App/Config/index.php";
require "vendor/coffeecode/router/src/RouterTrait.php";
require "vendor/coffeecode/router/src/Dispatch.php";
require "vendor/coffeecode/router/src/Router.php";


require "App/Controller/api/Groups.php";
require "App/Controller/api/User.php"; 
require "App/Controller/api/Cities.php";


use CoffeeCode\Router\Router;

$router = new Router(projectUrl: URL_BASE);
$router->namespace(namespace: "App\Controller");


$router->group(group: 'api');
$router->get(route: "/cidades", handler: "Cities:get");

$router->post(route: "/login", handler: "User:login");

$router->get(route: "/grupos", handler: "Groups:get");
$router->post(route: "/criargrupo", handler: "Groups:create");
$router->put(route: "/modificargrupo", handler: "Groups:change");
$router->delete(route: "/excluirgrupo/{id}", handler: "Groups:delete");

$router->dispatch();


if($router->error()) {
    var_dump($router->error()); 
}