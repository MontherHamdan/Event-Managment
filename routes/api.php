<?php

use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// middleware('auth:sanctum') to protect route from not authenticated user 
// return $user->request will fetch the user information if the user authenticated otherwise it will return null
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::resource('events', EventController::class);

// scoped means: the attendee Controller is always a part of event Controller
Route::resource('events.attendees', AttendeeController::class)
    ->scoped()->except(['update']);


// route for authentication token
Route::post('/login',  [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');
