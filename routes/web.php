<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\NotificationController;

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

// Rotta principale
Route::get('/', function () {
    return redirect()->route('login');
});

// Rotte per l'autenticazione
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rotte protette da autenticazione
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    
    // Rotte per la gestione degli utenti
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::get('/users/data', [UsersController::class, 'getUsers'])->name('users.data');
    Route::post('/users', [UsersController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UsersController::class, 'show'])->name('users.show');
    Route::put('/users/{user}', [UsersController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UsersController::class, 'destroy'])->name('users.destroy');
    
    // Rotte per le notifiche
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/list', [NotificationController::class, 'listPage'])->name('notifications.list'); // Nuova pagina per visualizzare tutte le notifiche
        Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
        Route::post('/mark-read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
        Route::post('/send', [NotificationController::class, 'send'])->name('notifications.send');
        Route::post('/send-to-all', [NotificationController::class, 'sendToAll'])->name('notifications.send-to-all');
        Route::post('/send-to-users', [NotificationController::class, 'sendToUsers'])->name('notifications.send-to-users');
        Route::get('/test', function () {
            return view('notifications.test');
        })->name('notifications.test');
        Route::get('/debug', function () {
            return view('notifications.debug');
        })->name('notifications.debug');
    });
});

// Solo per sviluppo: crea un utente di test
Route::get('/create-test-user', [AuthController::class, 'createTestUser'])->name('create.test.user');