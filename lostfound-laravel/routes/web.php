<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

/* Guest if user is not logged in or registered */
Route::middleware('guest')->group(function () {
    Route::get('/login', fn () => view('login'))->name('login');
    Route::get('/register', fn () => view('register'))->name('register');
    Route::post('/submit', [UserController::class, 'register'])->name('register.submit');
    Route::post('/signin', [UserController::class, 'signin'])->name('signin');
});

/* Auth once users in and completes either login or register form*/
Route::middleware('auth')->group(function () {
    Route::get('/index', fn () => view('index'))->name('index');
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    /*Creates and store */
    Route::get('/post-item', [ItemController::class, 'create'])->name('post-item');
    Route::post('/post-item', [ItemController::class, 'store'])->name('post-item.store');

    /*Displays items that are not marked as resolve in status */
    Route::get('/items', [ItemController::class, 'index']);

    /*Delete (destroy)*/
    Route::delete('/items/{item}', [ItemController::class, 'destroy']);

    /*If users click resolve button it updates status from active -> resolved */
    Route::patch('/items/{item}/resolve', [ItemController::class, 'resolve']);

    /*Contacting the user */
    Route::post('/items/{item}/contact', [ItemController::class, 'contact'])->name('items.contact');

    /*Letting user edit their own post/s */
    Route::middleware('auth')->group(function () {
    Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::patch('/items/{item}', [ItemController::class, 'update'])->name('items.update');
});

});

/* Admin */
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', fn () => view('admin.dashboard'))->name('admin.dashboard');
});
/*Route::get('/', function () {
    return view('register');
})->name('register');


Route::post('/submit',  [UserController::class,'register']);


Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/index', function () {
    return view('index');
})->name('index');

//new
/*Route::get('/register', function () {
    if (auth()->check()) {
        return redirect('/login'); // or /index
    }
    return view('register');
});*/

/*Route::get('/register', function () {
    return view('auth.register');
})->middleware('guest');


Route::post('/logout', [UserController::class, 'logout']);*/