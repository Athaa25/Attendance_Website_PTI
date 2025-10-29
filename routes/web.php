<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/manage-users', function () {
    return view('manage-user', ['page' => 'list']);
});

Route::get('/manage-users/view', function () {
    return view('manage-user', ['page' => 'view']);
});

Route::get('/manage-users/add', function () {
    return view('manage-user', ['page' => 'add']);
});

Route::get('/manage-users/edit', function () {
    return view('manage-user', ['page' => 'edit']);
});

Route::get('/daily-attendance', function () {
    return view('dailly-attendance', ['page' => 'list']);
});

Route::get('/daily-attendance/edit', function () {
    return view('dailly-attendance', ['page' => 'edit']);
});

Route::get('/sheet-report', function () {
    return view('sheet-report');
});

