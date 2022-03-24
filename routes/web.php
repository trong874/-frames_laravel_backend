<?php

use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\GroupController;
use App\Http\Controllers\Backend\ItemController;
use App\Http\Controllers\Backend\PagesController;
use App\Http\Controllers\Backend\SettingController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['auth','is_admin'])->group(function (){

    Route::get('/',function (){return redirect()->to('/admin/dashboard');});

    Route::get('/dashboard', [PagesController::class, 'index']);

    Route::resource('article', ItemController::class);

    Route::resource('article-group', GroupController::class);

    Route::resource('article-category', CategoryController::class);

    Route::resource('setting',SettingController::class);

    Route::get('duplicate-item/{id}', [ItemController::class, 'duplicateItem'])->name('duplicate-item');

    Route::get('get-item/{module}', [ItemController::class, 'ajaxGetItem'])->name('ajax_get_item');

    Route::get('get-group/{module}', [GroupController::class, 'ajaxGetGroup'])->name('ajax_get_group');
});

Route::get('test',function (){
});

require __DIR__.'/auth.php';
