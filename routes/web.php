<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->get('/items','ItemController@index');
$router->post('/items','ItemController@store');
$router->get('/items/{item}','ItemController@show');
$router->put('/items/{item}','ItemController@update');
$router->patch('/items/{item}','ItemController@update');
$router->delete('/items/{item}','ItemController@destroy');
