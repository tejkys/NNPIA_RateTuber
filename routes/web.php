<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\AccountController;
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
Route::get('/portal-admin', [AdminController::class, 'index'])->name('admin.index')->middleware(['role:admin']);
Route::get('/portal-admin/users', [AdminController::class, 'users'])->name('admin.users.index')->middleware(['role:admin']);
Route::post('/portal-admin/users', [AdminController::class, 'usersUpdate'])->name('admin.users.update')->middleware(['role:admin']);
Route::get('/portal-admin/channels', [AdminController::class, 'channels'])->name('admin.channels.index')->middleware(['role:admin']);
Route::post('/portal-admin/channels', [AdminController::class, 'channelsUpdate'])->name('admin.channels.update')->middleware(['role:admin']);
Route::post('/portal-admin/channels/search', [AdminController::class, 'channelsSearch'])->name('admin.channels.search')->middleware(['role:admin']);
Route::post('/portal-admin/channels/create', [AdminController::class, 'channelsCreate'])->name('admin.channels.create')->middleware(['role:admin']);

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::post('/find', [HomeController::class, 'search'])->name('home.search');
Route::get('/account', [AccountController::class, 'index'])->name('account.index');
Route::get('/account/logout', [AccountController::class, 'logout'])->name('account.logout');
Route::post('/account/create', [AccountController::class, 'create'])->name('account.create');
Route::post('/account/login', [AccountController::class, 'login'])->name('account.login');
Route::post('/account/changePassword', [AccountController::class, 'changePassword'])->name('account.changePassword');
Route::get('/{channel}', [ChannelController::class, 'get'])->name('channel.get');
Route::post('/{channel}/comment', [ChannelController::class, 'comment'])->name('channel.comment');
Route::get('/{channel}/thumbnail', [ChannelController::class, 'thumbnail'])->name('channel.thumbnail');
Route::get('/comment/delete/{comment}', [ChannelController::class, 'commentDelete'])->name('channel.commentDelete');

