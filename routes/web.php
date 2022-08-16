<?php
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
/** @var \Laravel\Lumen\Routing\Router $router */

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
$router->post('/login', [
    'as' => 'login', 'uses' => 'AuthController@login'
]);
// $router->get('/signup', function(){
//     return view();
// });
$router->post('/signup', [
    'as' => 'signup', 'uses' => 'AuthController@signup'
]);
$router->post('/users', [
    'as' => 'users', 'uses' => 'UserController@index'
]);
// $router->get('/users/create',[
//     'as' => 'users.create','uses' => 'UserController@create'
// ])->middleware('can:create-users');
$router->post('/sendemail', [
    'as' => 'sendemail', 'uses' => 'EmailController@emailRequestVerification']);
$router->post('/email/verify', [
    'as' => 'email.verify', 'uses' => 'AuthController@emailVerify']);
$router->post('/users/create-user',[
    'as' => 'users.create-user', 'uses' => 'UserController@create_user'
]);
$router->post('/users/list',[
    'as' => 'users.list', 'uses' => 'UserController@list'
]);
$router->post('admin/create-user', [
    'middleware' => 'auth',
    'uses' => 'AdminController@create_user'
]);
// $router->post('/password/reset-request', 'EmailController@sendResetLinkEmail');
