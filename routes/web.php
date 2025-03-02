<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('tasklist');
});

Route::controller(TaskController::class)->group(function(){
            Route::get('all-tasks','getTasks')->name('get.tasks'); 
             Route::get('not-completed-tasks','getIncompleteTasks')->name('get.incomplete.tasks');
            Route::post('add-task','addTask')->name('task.add');
            Route::post('complete-task','updateTask')->name('task.complete');
            Route::delete('delete-task/{id}','deleteTask')->name('delete.task');
});




