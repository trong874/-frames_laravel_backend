<?php

use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\GroupController;
use App\Http\Controllers\Backend\ItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('delete-multi-item',[ItemController::class,'destroyMulti'])->name('delete_multi_item');

Route::post('delete-multi-category',[CategoryController::class,'destroyMulti'])->name('delete_multi_category');

Route::post('delete-multi-group',[GroupController::class,'destroyMulti'])->name('delete_multi_group');

Route::get('search-item', [ItemController::class, 'searchItemGroup'])->name('items.search');

Route::post('change-order-category',[CategoryController::class,'changeOrder'])->name('change_order_category');

Route::post('insert-item-to-group', [GroupController::class, 'addItemIntoGroup'])->name('insert_item_to_group');

Route::post('get-item-in-group', [GroupController::class, 'getItemInGroup'])->name('get_item_in_group');

Route::post('delete-item-in-group', [GroupController::class, 'deleteItemInGroup'])->name('delete_item_in_group');

Route::post('change-order-item-in-group',[ItemController::class,'changeOrderInGroup'])->name('index.change-order-in-group');
