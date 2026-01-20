<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TicketController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [TicketController::class, 'show'])->name('events.show');

// purchase routes
use App\Http\Controllers\TicketPurchaseController;
use App\Http\Controllers\SimpleRegisterController;
Route::get('/events/{event}/purchase', [TicketPurchaseController::class, 'purchase'])->name('events.purchase');
Route::post('/events/{event}/purchase', [TicketPurchaseController::class, 'purchasePost'])->name('events.purchase.post')->middleware('auth');
Route::get('/my-tickets', [TicketPurchaseController::class, 'myTickets'])->name('tickets.my')->middleware('auth');

// Simple standalone registration page (plain HTML, avoids Blade components)
Route::get('/simple-register', [SimpleRegisterController::class, 'create'])->name('simple.register');
Route::post('/simple-register', [SimpleRegisterController::class, 'store'])->name('simple.register.store');


use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventAdminController;
use App\Http\Controllers\Admin\SeatAdminController;
use App\Http\Controllers\Admin\TicketAdminController;


Route::middleware(['auth', 'verified', \App\Http\Middleware\EnsureUserIsAdmin::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin/events/create', [EventAdminController::class, 'create'])->name('admin.events.create');
    Route::post('/admin/events', [EventAdminController::class, 'store'])->name('admin.events.store');
    Route::get('/admin/events/{event}/edit', [EventAdminController::class, 'edit'])->name('admin.events.edit');
    Route::put('/admin/events/{event}', [EventAdminController::class, 'update'])->name('admin.events.update');
    Route::delete('/admin/events/{event}', [EventAdminController::class, 'destroy'])->name('admin.events.destroy');
    Route::get('/admin/seats', [SeatAdminController::class, 'index'])->name('admin.seats.index');
    Route::get('/admin/seats/create', [SeatAdminController::class, 'create'])->name('admin.seats.create');
    Route::post('/admin/seats', [SeatAdminController::class, 'store'])->name('admin.seats.store');
    Route::get('/admin/seats/{seat}/edit', [SeatAdminController::class, 'edit'])->name('admin.seats.edit');
    Route::put('/admin/seats/{seat}', [SeatAdminController::class, 'update'])->name('admin.seats.update');
    Route::delete('/admin/seats/{seat}', [SeatAdminController::class, 'destroy'])->name('admin.seats.destroy');
    Route::get('/admin/tickets/scan', [TicketAdminController::class, 'scan'])->name('admin.tickets.scan');
    Route::post('/admin/tickets/scan', [TicketAdminController::class, 'processScan'])->name('admin.tickets.processScan');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
