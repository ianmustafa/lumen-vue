<?php

/** @var \Laravel\Lumen\Routing\Router $router */
$router->group(['prefix' => 'tasks'], function () use ($router) {
    $router->get('/', ['as' => 'tasks.index', 'uses' => 'TaskController@index']);
    $router->post('/', ['as' => 'tasks.store', 'uses' => 'TaskController@store']);
    $router->patch('{id}', ['as' => 'tasks.update', 'uses' => 'TaskController@update']);
    $router->delete('{id}', ['as' => 'tasks.destroy', 'uses' => 'TaskController@destroy']);
});
