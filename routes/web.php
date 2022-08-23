<?php
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
/** @var \Laravel\Lumen\Routing\Router $router */
// header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
// header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization, Accept,charset,boundary,Content-Length');
// header('Access-Control-Allow-Origin: *');
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
/**
 * Login and signup routes
 */
$router->post('/login', [
    'as' => 'login', 'uses' => 'AuthController@login'
]);
$router->post('/signup', [
    'as' => 'signup', 'uses' => 'AuthController@signup'
]);
/**
 * routes for verification of email, reset password and forgot password
 */
$router->post('/request-verification', [
    'middleware' => ['auth', 'verified'],
    'as' => 'email.request.verification', 
    'uses' => 'AuthController@emailRequestVerification'
]);
$router->post('/forgot-password',[
    'as' => 'email.forgot-password', 'uses' => 'AuthController@forgot_password'
]);
$router->post('/reset-password',[
    'as' => 'email.reset-password', 'uses' => 'AuthController@reset_password'
]);

$router->post('/verify', [
    'as' => 'email.verify', 'uses' => 'AuthController@emailVerify']);
/**
 * Creation of user by another user
 */
$router->post('/users/create-user',[
    'as' => 'users.create-user', 'uses' => 'UserController@create_user'
]);
/**
 * Basic filtering and listing of users
 */
$router->post('/users/list',[
    'as' => 'users.list', 'uses' => 'UserController@list'
]);
/**
 * Deletion of user, only done by the Admin
 */
$router->post('admin/delete-user', [
    'middleware' => 'auth',
    'uses' => 'AdminController@delete_user'
]);
