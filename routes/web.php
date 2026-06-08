<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Redirect roots to their panels
Route::redirect('/admin-login', '/admin/login');
Route::redirect('/provider-login', '/provider/login');
