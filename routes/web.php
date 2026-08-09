<?php

use App\Http\Controllers\Teams\TeamInvitationController;
use App\Http\Controllers\Boards\BoardListController;
use App\Http\Controllers\Boards\CardController;
use App\Http\Controllers\Boards\BoardController;
use App\Http\Middleware\EnsureTeamMembership;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::prefix('{current_team}')
    ->middleware(['auth', 'verified', EnsureTeamMembership::class])
    ->group(function () {
        Route::get('dashboard', DashboardController::class)->name('dashboard');
    });

Route::middleware(['auth'])->group(function () {
    Route::get('invitations/{invitation}/accept', [TeamInvitationController::class, 'accept'])->name('invitations.accept');
    Route::delete('invitations/{invitation}', [TeamInvitationController::class, 'decline'])->name('invitations.decline');
});

Route::prefix('{current_team}')->middleware(['auth', 'verified', App\Http\Middleware\EnsureTeamMembership::class])->group(function () {
    Route::get('boards', [BoardController::class, 'index'])->name('boards.index');
    Route::post('boards', [BoardController::class, 'store'])->name('boards.store');
    Route::get('boards/{board:slug}', [BoardController::class, 'show'])->name('boards.show');
    
    Route::post('boards/{board:slug}/lists', [BoardListController::class, 'store'])->name('boards.lists.store');
    Route::patch('boards/{board:slug}/lists/{list}', [BoardListController::class, 'update'])->name('boards.lists.update');
    Route::delete('boards/{board:slug}/lists/{list}', [BoardListController::class, 'destroy'])->name('boards.lists.destroy');
    Route::post('boards/{board:slug}/lists/{list}/cards', [CardController::class, 'store'])->name('boards.lists.cards.store');
    Route::patch('boards/{board:slug}/cards/{card}', [CardController::class, 'update'])->name('boards.cards.update');
    Route::delete('boards/{board:slug}/cards/{card}', [CardController::class, 'destroy'])->name('boards.cards.destroy');
});


require __DIR__.'/settings.php';
