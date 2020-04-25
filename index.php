<?php
ob_start();

use CoffeeCode\Router\Router;

require __DIR__ .'/bootstrap.php';

$router = new Router(CONF_URL_BASE, '@');

$router->namespace("App\Controllers");

$router->get("/", "TesteController@home");
$router->post("/login", "PersonController@login");
$router->get("/logout", "PersonController@logout");

$router->post("/cadastrar/cliente", "ClientController@register");
$router->post("/atualizar/cliente", "ClientController@update");
$router->get("/clientes", "ClientController@getAllClients");
$router->get("/cliente/{id}/remove", "ClientController@remove");

$router->post("/cadastrar/recepcionista", "ReceptionistController@register");
$router->get("/recepcionistas", "ReceptionistController@getAllReceptionist");
$router->post("/atualizar/recepcionista", "ReceptionistController@update");
$router->get("/recepcionista/{id}/remove", "ReceptionistController@remove");

$router->post("/cadastrar/barbeiro", "BarberController@register");
$router->get("/barbeiros", "BarberController@getAllBarbers");
$router->post("/atualizar/barbeiro", "BarberController@update");
$router->get("/barbeiro/{id}/remove", "BarberController@remove");

$router->get("/error/{error}", "TesteController@error");
/**
 * This method executes the routes
 */
$router->dispatch();

/*
 * Redirect all errors
 */
if ($router->error()) {
    $router->redirect("/error/{$router->error()}");
}

ob_end_flush();