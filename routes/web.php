<?php
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/** @var \Laravel\Lumen\Routing\Router $router */
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization, Accept,charset,boundary,Content-Length,authorization');
header('Access-Control-Allow-Origin: *');
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
// $router->post('/request-verification', [
//     'middleware' => ['auth', 'verified'],
//     'as' => 'email.request.verification', 
//     'uses' => 'AuthController@emailRequestVerification'
// ]);
$router->post('/forgot-password',[
    'as' => 'email.forgot-password', 'uses' => 'AuthController@forgot_password'
]);
$router->post('/reset-password',[
    'as' => 'email.reset-password', 'uses' => 'AuthController@reset_password'
]);

// $router->post('/verify', [
//     'as' => 'email.verify', 'uses' => 'AuthController@emailVerify']);
/**
 * Creation of user by another user
 */
$router->post('/users/create-user',[
    'middleware' => 'auth',
    'as' => 'users.create-user', 'uses' => 'UserController@create_user'
]);
/**
 * Basic filtering and listing of users
 */
$router->post('/users/list',[
    'middleware' => 'auth',
    'as' => 'users.list', 'uses' => 'UserController@list'
]);
/**
 * Deletion of user, only done by the Admin
 */
$router->post('admin/delete-user', [
    'middleware' => ['auth', 'admin'],
    'uses' => 'AdminController@delete_user'
]);

$router->post('/broadcasting/auth', [
    'middleware' => 'auth',
    'as' => 'broadcast.authenticate', 
    'uses' => 'BroadcastController@authenticate'
]);
/**
 * Task Management
 */
// Auth::routes();
 $router->post('/tasks/view/assigned',[
    'middleware' => 'auth',
    'as' => 'tasks.view', 'uses' => 'TaskController@assignedList'
 ]);

 $router->post('/tasks/view/created',[
    'middleware' => 'auth',
    'as' => 'tasks.view', 'uses' => 'TaskController@createdList'
 ]);

 $router->post('/tasks/create',[
    'middleware' => 'auth',
    'as' => 'tasks.create', 'uses' => 'TaskController@create'
 ]);

 $router->post('/tasks/update',[
    'middleware' => 'auth',
    'as' => 'tasks.update', 'uses' => 'TaskController@update'
 ]);

 $router->post('tasks/updateStatus',[
    'middleware' => 'auth',
    'as' => 'tasks.updateStatus', 'uses' => 'TaskController@updateStatus'
 ]);
 $router->post('tasks/upcoming',[
    'middleware' => 'auth',
    'as' => 'tasks.upcoming', 'uses' => 'TaskController@upcoming'
 ]);
 $router->post('tasks/overdue',[
    'middleware' => 'auth',
    'as' => 'tasks.overdue', 'uses' => 'TaskController@overdue'
 ]);
 $router->post('tasks/complete',[
    'middleware' => 'auth',
    'as' => 'tasks.complete', 'uses' => 'TaskController@complete'
 ]);
 $router->post('tasks/inProgress',[
    'middleware' => 'auth',
    'as' => 'tasks.inProgress', 'uses' => 'TaskController@inProgress'
 ]);
 $router->post('notifs/read',[
    'middleware' => 'auth',
    'as' => 'notifs.read', 'uses' => 'NotificationController@readNotif'
 ]);
 $router->post('notifs/get',[
    'middleware' => 'auth',
    'as' => 'notifs.get', 'uses' => 'NotificationController@getNotifs'
 ]);