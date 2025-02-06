<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CarWashController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientListController;
use App\Http\Controllers\EmployeeListController;
use App\Http\Controllers\RecurringAssignmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkAssignmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/', [ClientController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [ClientController::class, 'register']);
//students
Route::group(['prefix' => 'student'], function () {

    //guest
    Route::group(['middleware' => 'guest'], function () {
        Route::get('login', [UserController::class, 'index'])->name('student.login');
        Route::post('authenticate', [UserController::class, 'authenticate'])->name('student.authenticate');
    });

    //auth
    Route::group(['middleware' => 'auth'], function () {
        Route::get('dashboard', [UserController::class, 'dashboard'])->name('student.dashboard');
        Route::get('logout', [UserController::class, 'logout'])->name('student.logout');
        // Route::get('dashboard', [CarWashController::class, 'showBookingForm'])->name('student.dashboard');

        Route::post('booking', [CarWashController::class, 'storeBooking'])->name('student.booking');
        Route::get('bookings', [CarWashController::class, 'viewBookings'])->name('student.bookings');

        // Student routes
        // ... existing routes ...
        Route::get('student/booking-status/{bookingId}', [CarWashController::class, 'bookingStatus'])->name('student.booking-status');
        // ... existing routes ...
        Route::get('statusall', [CarWashController::class, 'bookingStatusall'])->name('student.booking-statusall');
    });
});

// Admin routes
Route::group(['prefix' => 'admin'], function () {
    // Routes for guests (e.g., login, register)
    Route::group(['middleware' => 'admin.guest'], function () {
        Route::get('login', [AdminController::class, 'index'])->name('admin.login');
        Route::post('login', [AdminController::class, 'authenticate'])->name('admin.authenticate');
        Route::get('register', [AdminController::class, 'register'])->name('admin.register');
        Route::post('register', [AdminController::class, 'clientsave'])->name('admin.clientsave');
    });

    // Routes for authenticated admins
    Route::group(['middleware' => 'admin.auth'], function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('form', [AdminController::class, 'form'])->name('admin.form');
        Route::get('table', [AdminController::class, 'table'])->name('admin.table');
        Route::get('logout', [AdminController::class, 'logout'])->name('admin.logout');
        Route::get('bookinglist', [AdminController::class, 'bookinglist'])->name('admin.bookinglist');


        //EmployeeListController
        Route::get('employeelist', [EmployeeListController::class, 'index'])->name('admin.employeelist');
        Route::get('employeeform', [EmployeeListController::class, 'employeeform'])->name('admin.employeeform');
        Route::post('employeeform', [EmployeeListController::class, 'store'])->name('admin.store');

        //ClientListController
        Route::get('clientlist', [ClientListController::class, 'index'])->name('admin.clientlist');
        Route::post('clientstore', [EmployeeListController::class, 'clientstore'])->name('admin.clientstore');
        Route::get('clientform', [EmployeeListController::class, 'clientform'])->name('admin.clientform');
        //assignwork

        Route::get('assign-work/{booking}', [WorkAssignmentController::class, 'create'])->name('admin.assign-work');
        Route::post('assign-work/{booking}', [WorkAssignmentController::class, 'store'])->name('admin.assign-work.store');


        // Admin routes
        Route::group(['middleware' => 'admin.auth'], function () {
            // ... existing routes ...
            Route::get('booking-details/{booking}', [AdminController::class, 'bookingDetails'])->name('admin.booking-details');
        });


        // Admin routes
        Route::get('regular-clients', [AdminController::class, 'regularClients'])->name('admin.regular-clients');
        Route::post('mark-regular-client/{user}', [AdminController::class, 'markAsRegular'])->name('admin.mark-regular');
        Route::resource('recurring-assignments', RecurringAssignmentController::class);
    });
});

// Teacher routes
Route::group(['prefix' => 'teacher'], function () {
    // Routes for guests (e.g., login, register)
    Route::group(['middleware' => 'teacher.guest'], function () {
        Route::get('login', [EmployeeListController::class, 'login'])->name('teacher.login');
        Route::post('authenticate', [EmployeeListController::class, 'authenticate'])->name('teacher.authenticate');
    });
    // Routes for authenticated teacher
    Route::group(['middleware' => 'teacher.auth'], function () {
        // Dashboard and basic routes
        Route::get('dashboard', [EmployeeListController::class, 'dashboard'])->name('teacher.dashboard');
        Route::get('logout', [EmployeeListController::class, 'logout'])->name('teacher.logout');

        // Work assignment routes
        Route::get('assignwork', [EmployeeListController::class, 'assignwork'])->name('teacher.assignwork');
        Route::get('assigned-works', [EmployeeListController::class, 'assignedWorks'])->name('teacher.assigned-works');

        // Work update routes (single entry)
        Route::prefix('update-work')->group(function () {
            Route::get('/{assignment}', [EmployeeListController::class, 'showUpdateForm'])
                ->name('teacher.update-work');
            Route::put('/{assignment}', [EmployeeListController::class, 'updateWork'])
                ->name('teacher.update-work.put');
        });

        // Daily updates routes
        Route::prefix('daily-updates')->group(function () {
            Route::get('/', [EmployeeListController::class, 'dailyUpdatesList'])
                ->name('teacher.daily-updates');
            Route::get('/create/{assignment}', [EmployeeListController::class, 'dailyUpdate'])
                ->name('teacher.daily-update.create');
            Route::post('/store/{assignment}', [EmployeeListController::class, 'storeDailyUpdate'])
                ->name('teacher.daily-update.store');
        });
    });
});
