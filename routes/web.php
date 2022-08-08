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
$router->get('/login', function () {
    return view();
});
// $router->get('/signup', function(){
//     return view();
// });
$router->post('/signup', [
    'as' => 'signup', 'uses' => 'UserController@signup'
]);
$router->get('/userlisting', [
    'as' => 'userlisting', 'uses' => 'UserController@userlisting'
]);
$router->get('/sendmail', [
    'as' => 'send-mail', 'uses' => 'UserController@sendmail'
]);


