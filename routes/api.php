<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:api'], function () {
    //Put routes here that you want to protect with login
    //You need to include the line below in your controller
    //use Illuminate\Support\Facades\Auth;
    //With "$user = Auth::user()" you get the authenticated user
   
    Route::post('/logout', 'UserController@logout');
    
    Route::get('/getAuthUser', 'UserController@getAuthUser');
});

//user
Route::get('/user/{id}', 'UserController@showOne');
Route::get('/user/{id}/getAll', 'UserController@getAllValues');
Route::post('/user', 'UserController@store');  
Route::get('/user', 'UserController@showAll'); 
Route::put('/user/{id}', 'UserController@update');
//Route::delete('/user/{id}', 'UserController@destroy');      werkt nog niet

//custom user actions
Route::get('/user/rooms/{id}', 'UserController@getRooms');

//user
Route::post('/room_user', 'RoomUserController@store');

//SensorModule
Route::get('/sensormodule/{id}', 'SensorModuleController@showOne');
Route::get('/sensormodule', 'SensorModuleController@showAll');
Route::post('/sensormodule', 'SensorModuleController@store');
Route::put('/sensormodule/{id}', 'SensorModuleController@update');
//Route::delete('/sensormodule/{id}', 'SensorModuleController@destroy');      werkt nog niet

//custom SensorModule actions
Route::get('/sensormodule/rooms/{id}', 'SensorModuleController@getRooms');
Route::get('/sensormodule/dataregister/{id}', 'SensorModuleController@getDataRegisters');

//room
Route::get('/room/getAll', 'RoomController@getAllValues');
Route::get('/room/{id}', 'RoomController@showOne');
Route::get('/room/{id}/getAll', 'RoomController@getAllValuesRoom');
Route::get('/room', 'RoomController@showAll');
Route::post('/room', 'RoomController@store');
Route::put('/room/{id}', 'RoomController@update');
Route::delete('/room/{id}', 'RoomController@destroy');      

//custom room actions
Route::get('/room/users/{id}', 'RoomController@getUsers');
Route::get('/room/sensormodules/{id}', 'RoomController@getSensorModules');

//field
Route::get('/field/{id}', 'FieldController@showOne');
Route::get('/field', 'FieldController@showAll');
Route::post('/field', 'FieldController@store');
Route::put('/field/{id}', 'FieldController@update');
//Route::delete('/field/{id}', 'FieldController@destroy');      werkt nog niet

//dataregister
Route::get('/dataregister/{id}', 'DataRegisterController@showOne');
Route::get('/dataregister', 'DataRegisterController@showAll');
Route::post('/dataregister', 'DataRegisterController@store');
Route::put('/dataregister/{id}', 'UserDataRegisterControllerController@update');
//Route::delete('/dataregister/{id}', 'UserDataRegisterControllerController@destroy');      werkt nog niet

//custom room actions
Route::get('/dataregister/sensormodules/{id}', 'RoomController@getSensorModules');

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/login', 'UserController@login');
Route::post('/login/forget', 'UserController@resetPasswordemail');
Route::post('/login/wachtwoord/update', 'UserController@resetPassword');