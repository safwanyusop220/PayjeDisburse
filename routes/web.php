<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AllocationController;
use App\Http\Controllers\AllocationWaitingApprovalController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentExportController;
use App\Http\Controllers\PaymentResource;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ProgramWaitingApprovalController;
use App\Http\Controllers\ReceiverController;
use App\Http\Controllers\RemoveRoleFromUserController;
use App\Http\Controllers\RevokePermissionFromController;
use App\Http\Controllers\RevokePermissionFromUserController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Admin/Dashboards/DashboardIndex');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth', 'role:admin')->prefix('/admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');

    Route::resource('/users', UserController::class);

    Route::resource('/roles', RoleController::class);

    Route::resource('/permissions', PermissionController::class);

    Route::delete('/roles/{role}/permissions/{permission}', RevokePermissionFromController::class)
        ->name('roles.permissions.destroy');

    Route::delete('/users/{user}/permissions/{permission}', RevokePermissionFromUserController::class)
        ->name('users.permissions.destroy');

    Route::delete('/users/{user}/roles/{role}', RemoveRoleFromUserController::class)
        ->name('users.roles.destroy');
});

Route::resource('/posts', PostController::class)->middleware(['role:admin|moderator|writer|']);

Route::resource('/dashboards', DashboardController::class)->middleware(['role:admin|moderator|writer|']);

Route::prefix('allocations')->middleware(['role:admin|moderator|writer'])->group(function () {
    Route::get('/', [AllocationController::class, 'index'])->name('allocations.index');
    Route::post('/', [AllocationController::class, 'store'])->name('allocations.store');
    Route::get('/create', [AllocationController::class, 'create'])->name('allocations.create');
    Route::get('/{allocation}/edit', [AllocationController::class, 'edit'])->name('allocations.edit');
    Route::put('/{allocation}', [AllocationController::class, 'update'])->name('allocations.update');
    Route::delete('/{allocation}', [AllocationController::class, 'destroy'])->name('allocations.destroy');
    Route::get('/allocation-approval/{id}', [AllocationController::class, 'allocationApproval'])->name('allocations.allocationApproval');
    Route::put('/approve/{id}', [AllocationController::class, 'approve'])->name('allocations.approve');
    Route::post('/reject/{id}', [AllocationController::class, 'reject'])->name('allocations.reject');
    Route::get('/allocations-waiting-approval', [AllocationController::class, 'waitingApproval'])->name('allocations.waitingApproval');
});

Route::prefix('programs')->middleware(['role:admin|moderator|writer'])->group(function () {
    Route::get('/', [ProgramController::class, 'index'])->name('programs.index');
    Route::post('/', [ProgramController::class, 'store'])->name('programs.store');
    Route::get('/create', [ProgramController::class, 'create'])->name('programs.create');
    Route::get('/{program}/edit', [ProgramController::class, 'edit'])->name('programs.edit');
    Route::put('/{program}', [ProgramController::class, 'update'])->name('programs.update');
    Route::delete('/{program}', [ProgramController::class, 'destroy'])->name('programs.destroy');
    Route::get('/program-approval/{id}', [ProgramController::class, 'programApproval'])->name('programs.programApproval');
    Route::put('/approve/{id}', [ProgramController::class, 'approve'])->name('programs.approve');
    Route::post('/reject/{id}', [ProgramController::class, 'reject'])->name('programs.reject');
    Route::get('/programs-waiting-approval', [ProgramController::class, 'waitingApproval'])->name('programs.waitingApproval');
});


Route::prefix('receivers')->middleware(['role:admin|moderator|writer'])->group(function () {
    Route::get('/', [ReceiverController::class, 'index'])->name('receivers.index');
    Route::post('/', [ReceiverController::class, 'store'])->name('receivers.store');
    Route::get('/create', [ReceiverController::class, 'create'])->name('receivers.create');
    Route::get('/{receiver}', [ReceiverController::class, 'show'])->name('receivers.show');
    Route::get('/{receiver}/edit', [ReceiverController::class, 'edit'])->name('receivers.edit');
    Route::put('/{receiver}', [ReceiverController::class, 'update'])->name('receivers.update');
    Route::delete('/{receiver}', [ReceiverController::class, 'destroy'])->name('receivers.destroy');
    Route::get('/receiver-approval/{id}', [ReceiverController::class, 'receiverApproval'])->name('receivers.receiverApproval');
    Route::put('/approve/{id}', [ReceiverController::class, 'approve'])->name('receivers.approve');
    Route::post('/reject/{id}', [ReceiverController::class, 'reject'])->name('receivers.reject');
});
Route::get('/receivers-waiting-approval', [ReceiverController::class, 'waitingApproval'])->name('receivers.waitingApproval');



Route::prefix('payments')->middleware(['role:admin|moderator|writer'])->group(function () {
    Route::get('/', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/exports', [PaymentController::class, 'export'])->name('payments.export');
    Route::post('/imports', [PaymentController::class, 'import'])->name('payments.import');
    Route::get('/program-receiver-list/{id}', [PaymentController::class, 'programReceiverList'])->name('payments.programReceiverList');
});

















// Approval
//Route::get('/accept-post/{id}', 'ApprovalController@approveAllocation')->middleware(['role:admin|moderator|writer|']);





require __DIR__ . '/auth.php';
