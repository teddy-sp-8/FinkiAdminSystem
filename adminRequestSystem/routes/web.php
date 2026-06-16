<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminStudentController;
use App\Http\Controllers\Admin\RequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\AdministrativeRequestController;
use App\Http\Controllers\Student\AIAnalysisController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $requestTypes = \App\Models\RequestType::all();
    return view('welcome', compact('requestTypes'));
})->name('home');

require __DIR__ . '/auth.php';

Route::get('/dashboard', function () {
    if (auth()->user()->is_admin) {
        return redirect()->route('admin.requests.index');
    }

    return redirect()->route('student.requests.index');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware('auth')->prefix('student')->name('student.')->group(function () {
    Route::get('/requests', [AdministrativeRequestController::class, 'index'])
        ->name('requests.index');

    Route::get('/requests/create', [AdministrativeRequestController::class, 'create'])
        ->name('requests.create');

    Route::post('/requests', [AdministrativeRequestController::class, 'store'])
        ->name('requests.store');

    Route::get('/requests/{id}/edit', [AdministrativeRequestController::class, 'edit'])
        ->name('requests.edit');

    Route::put('/requests/{id}', [AdministrativeRequestController::class, 'update'])
        ->name('requests.update');
    Route::get('/requests/{id}', [AdministrativeRequestController::class, 'show'])->name('requests.show');
    Route::get('/requests/{id}', [AdministrativeRequestController::class, 'show'])->name('requests.show');

    Route::post('/ai/check', [AIAnalysisController::class, 'analyze']);

    Route::post('/requests/{id}/simulate-payment', [App\Http\Controllers\Student\AdministrativeRequestController::class, 'simulatePayment'])
        ->name('requests.simulatePayment');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/requests', [RequestController::class, 'index'])
        ->name('requests.index');

    Route::get('/requests/create', [RequestController::class, 'create'])
        ->name('requests.create');

    Route::post('/requests', [RequestController::class, 'store'])
        ->name('requests.store');


    Route::get('/dashboard_stats', [AdminDashboardController::class, 'index'])
        ->name('dashboard_stats');

    Route::get('/requests/{request}', [RequestController::class, 'show'])
        ->name('requests.show');

    Route::put('/requests/{adminRequest}/status', [RequestController::class, 'updateStatus'])
        ->name('requests.updateStatus');


    Route::get('/students', [AdminStudentController::class, 'index'])
        ->name('students.index');
    Route::get('/students/{id}', [AdminStudentController::class, 'show'])
        ->name('students.show');
    Route::get('/service-configurator', [RequestController::class, 'typesIndex'])->name('service_config.index');
    Route::post('/service-configurator', [RequestController::class, 'typesStore'])->name('service_config.store');
    Route::delete('/service-configurator/{id}', [RequestController::class, 'typesDestroy'])->name('service_config.destroy');

});


Route::post('/ai/check', function (Request $request) {
    $action = app(\App\Actions\AskAiAgentAction::class);

    $suggestion = $action->execute(
        $request->input('description'),
        $request->input('request_type')
    );

    return response()->json([
        'success' => true,
        'suggestion' => $suggestion
    ]);
})->middleware('auth');
