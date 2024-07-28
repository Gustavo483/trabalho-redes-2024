<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::controller(GroupController::class)->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard');
        Route::get('/show/{group}', 'show')->name('show');
        Route::post('/sendMessege/{group}', 'sendMessege')->name('sendMessege');
        Route::post('/newGroup/{user}', 'newGroup')->name('newGroup');

        Route::get('/rejectInvitation{group}', 'rejectInvitation')->name('rejectInvitation');
        Route::get('/acceptInvite{group}', 'acceptInvite')->name('acceptInvite');

        Route::get('/rejectInvitationAdmin/{group}/{user}', 'rejectInvitationAdmin')->name('rejectInvitationAdmin');
        Route::get('/acceptInviteAdmin/{group}/{user}', 'acceptInviteAdmin')->name('acceptInviteAdmin');

        Route::get('/sendRequesToGroup/{group}', 'sendRequesToGroup')->name('sendRequesToGroup');


        Route::get('/showGroups', 'showGroups')->name('showGroups');

    });
});

require __DIR__.'/auth.php';
