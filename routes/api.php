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

$router->group([
    'prefix'    => 'api/v1', 'middleware' => ['cors', 'log']] , function () use ($router) {

    $router->post('users/', ['uses' => 'User\UserController@store']);
    $router->post('password/email', ['uses' => 'Auth\ForgotPasswordController@getResetToken']);
    $router->post('password/reset', ['uses' => 'Auth\ResetPasswordController@reset']);

    $router->group(['middleware' => 'auth'], function () use ($router) {

        $router->group([
            'prefix'    => '/users',
            'namespace' => 'User'], function () use ($router) {
                $router->get('/', ['uses' => 'UserController@index']);
                $router->get('/{id:[\d]+}', ['as' => 'users.show', 'uses' => 'UserController@show']);
                $router->put('/{id:[\d]+}', ['as' => 'users.update', 'uses' => 'UserController@update']);
                $router->delete('/{id:[\d]+}', ['as' => 'users.destroy', 'uses' => 'UserController@destroy']);
        });

        $router->group([
            'prefix'    => '/children',
            'namespace' => 'User'], function () use ($router) {
                $router->get('/', ['uses' => 'ChildController@index']);
                $router->post('/', ['as' => 'child.create', 'uses' => 'ChildController@store']);
                $router->get('/{id:[\d]+}', ['as' => 'children.show', 'uses' => 'ChildController@show']);
        });
    });
});
