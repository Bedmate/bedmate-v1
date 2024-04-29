<?php

use App\Events\Playground;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\MiscController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
    return get_success_response([
        "message" =>  "In the time of myth, in the kingdom of fun, there lived a platform : named Cumrid............"
    ]);
});

// Route::get('/upload', 'FileUploadController@showUploadForm');

Route::post('/upload', [FileUploadController::class, 'upload']);

// Route::get('email', function()  {
//     return view('mail.email');
// });

Route::get('sample', function () {
    $password = Hash::make('password');
    for ($i = 0; $i < 30; $i++) {
        User::factory()->create([
            'name' => fake()->name(),
            'email' => fake()->companyEmail(),
            'is_escort' => true,
            'password' => $password,
            'username' => fake()->userName(),
            'plans' => 'freemium'
        ]);
    }
});


// routes/web.php
Route::get('/images', [MiscController::class, 'images'])->name('images');
//
Route::post('/compare-images', [MiscController::class, 'compareImages'])->name('compare-images');
