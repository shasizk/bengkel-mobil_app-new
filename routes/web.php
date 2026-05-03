<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminRatingController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ClientRatingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\MechanicAttendanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/client', [HomeController::class, 'index']);
Route::get('/about', [HomeController::class, 'about'])->name('about');

Route::get('/dashboard', function () {
    $admin = backend_user();
    $client = auth('client')->user();

    if ($admin) {
        return match ($admin->role) {
            'kasir' => redirect()->route('admin.transactions.index', ['ctx' => backend_guard()]),
            'mekanik' => redirect()->route('admin.booking.index', ['ctx' => backend_guard()]),
            default => redirect()->route('admin.dashboard.index', ['ctx' => backend_guard()]),
        };
    }

    if ($client) {
        return redirect()->route('home');
    }

    return redirect()->route('home');
})->name('dashboard');

Route::post('/session/refresh', function () {
    // This route refreshes the session by touching the user
    // Just returning OK is enough to refresh the session
    return response()->json(['success' => true]);
})->middleware('auth:web,client,admin,owner,kasir,mekanik')->name('session.refresh');

Route::post('/client/booking', [BookingController::class, 'store'])
    ->name('client.booking.store');

Route::post('/midtrans-callback', [TransactionController::class, 'callback'])->name('midtrans.callback');
Route::post('/midtrans-client-update', [TransactionController::class, 'clientUpdateStatus'])->name('midtrans.client-update');
Route::get('/midtrans-finish', [TransactionController::class, 'paymentFinish'])->name('client.payment.finish');

Route::prefix('admin')->name('admin.')->middleware('backend.auth')->group(function () {
    Route::get('/', [AdminController::class, 'index'])
        ->middleware('role:admin,mekanik,kasir,owner')
        ->name('dashboard.index');
    Route::get('/dashboard', [AdminController::class, 'index'])
        ->middleware('role:admin,mekanik,kasir,owner')
        ->name('dashboard.index');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');

    Route::prefix('booking')->name('booking.')->middleware('role:admin,mekanik,kasir,owner')->group(function () {
        Route::get('/', [BookingController::class, 'index'])->name('index');
        Route::get('/{id}', [BookingController::class, 'show'])->name('show');
        Route::patch('/update-status/{id}/{status}', [BookingController::class, 'updateStatus'])
            ->middleware('role:admin,mekanik')
            ->name('updateStatus');

        Route::get('/update-status/{id}/{status}', function () {
            return redirect()
                ->to(backend_route('admin.booking.index'))
                ->with('error', 'Aksi ubah status harus melalui tombol aksi pada halaman booking.');
        })->middleware('role:admin,mekanik');
    });

    Route::prefix('services')->name('services.')->group(function () {
        Route::get('/', [ServiceController::class, 'index'])->middleware('role:admin,owner')->name('index');
        Route::get('/create', [ServiceController::class, 'create'])->middleware('role:admin')->name('create');
        Route::post('/', [ServiceController::class, 'store'])->middleware('role:admin')->name('store');
        Route::get('/{service}/edit', [ServiceController::class, 'edit'])->middleware('role:admin')->name('edit');
        Route::match(['put', 'patch'], '/{service}', [ServiceController::class, 'update'])->middleware('role:admin')->name('update');
        Route::delete('/{service}', [ServiceController::class, 'destroy'])->middleware('role:admin')->name('destroy');
    });

    Route::prefix('vehicles')->name('vehicles.')->group(function () {
        Route::get('/', [VehicleController::class, 'index'])->middleware('role:admin,owner')->name('index');
        Route::get('/create', [VehicleController::class, 'create'])->middleware('role:admin')->name('create');
        Route::post('/', [VehicleController::class, 'store'])->middleware('role:admin')->name('store');
        Route::get('/{vehicle}', [VehicleController::class, 'show'])->middleware('role:admin,owner')->name('show');
        Route::get('/{vehicle}/edit', [VehicleController::class, 'edit'])->middleware('role:admin')->name('edit');
        Route::match(['put', 'patch'], '/{vehicle}', [VehicleController::class, 'update'])->middleware('role:admin')->name('update');
        Route::delete('/{vehicle}', [VehicleController::class, 'destroy'])->middleware('role:admin')->name('destroy');
    });

    Route::prefix('spareparts')->name('spareparts.')->group(function () {
        Route::get('/', [SparepartController::class, 'index'])->middleware('role:admin,owner')->name('index');
        Route::get('/create', [SparepartController::class, 'create'])->middleware('role:admin,owner')->name('create');
        Route::post('/', [SparepartController::class, 'store'])->middleware('role:admin,owner')->name('store');
        Route::get('/{sparepart}', [SparepartController::class, 'show'])->middleware('role:admin,owner')->name('show');
        Route::get('/{sparepart}/edit', [SparepartController::class, 'edit'])->middleware('role:admin,owner')->name('edit');
        Route::match(['put', 'patch'], '/{sparepart}', [SparepartController::class, 'update'])->middleware('role:admin,owner')->name('update');
        Route::delete('/{sparepart}', [SparepartController::class, 'destroy'])->middleware('role:admin')->name('destroy');
    });

    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->middleware('role:admin,kasir,owner')->name('index');
        Route::get('/create', [TransactionController::class, 'create'])->middleware('role:admin,kasir,owner')->name('create');
        Route::post('/', [TransactionController::class, 'store'])->middleware('role:admin,kasir,owner')->name('store');
        Route::get('/{transaction}', [TransactionController::class, 'show'])->middleware('role:admin,kasir,owner')->name('show');
        Route::get('/{transaction}/pdf', [TransactionController::class, 'pdf'])->middleware('role:admin,kasir,owner')->name('pdf');
    });

    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', [MechanicAttendanceController::class, 'index'])->middleware('role:mekanik,owner')->name('index');
        Route::post('/', [MechanicAttendanceController::class, 'store'])->middleware('role:mekanik')->name('store');
    });

    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->middleware('role:admin')->name('index');
        Route::post('/', [ChatController::class, 'store'])->middleware('role:admin')->name('store');
    });

    Route::prefix('ratings')->name('ratings.')->group(function () {
        Route::get('/', [AdminRatingController::class, 'index'])->middleware('role:admin,owner')->name('index');
        Route::patch('/{rating}/reply', [AdminRatingController::class, 'reply'])->middleware('role:admin')->name('reply');
    });

    Route::prefix('ledger')->name('ledger.')->group(function () {
        Route::get('/', [LedgerController::class, 'index'])->middleware('role:admin,owner')->name('index');
        Route::post('/', [LedgerController::class, 'store'])->middleware('role:admin,owner')->name('store');
    });

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->middleware('role:admin,owner')->name('index');
        Route::get('/create', [UserManagementController::class, 'create'])->middleware('role:admin')->name('create');
        Route::post('/', [UserManagementController::class, 'store'])->middleware('role:admin')->name('store');
        Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->middleware('role:admin')->name('edit');
        Route::match(['put', 'patch'], '/{user}', [UserManagementController::class, 'update'])->middleware('role:admin')->name('update');
    });
});

Route::prefix('client')->name('client.')->middleware('auth:client')->group(function () {
    Route::get('/booking', [HomeController::class, 'booking'])
        ->middleware('role:customer')
        ->name('booking.index');
    Route::get('/profile', [ProfileController::class, 'clientProfile'])
        ->middleware('role:customer')
        ->name('profile');
    Route::get('/history', [ProfileController::class, 'clientHistory'])
        ->middleware('role:customer')
        ->name('history');
    Route::get('/rating', [ClientRatingController::class, 'index'])
        ->middleware('role:customer')
        ->name('rating.index');
    Route::post('/rating', [ClientRatingController::class, 'store'])
        ->middleware('role:customer')
        ->name('rating.store');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');
    Route::get('/chat', [ChatController::class, 'index'])->middleware('role:customer')->name('chat.index');
    Route::post('/chat', [ChatController::class, 'store'])->middleware('role:customer')->name('chat.store');
});

require __DIR__.'/auth.php';
