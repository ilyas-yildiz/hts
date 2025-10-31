<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// HTS Kontrolcülerimizi buraya dahil et
use App\Http\Controllers\Api\GoalController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\ReorderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- BREEZE WEB ARAYÜZ ROTLARI ---

// DÜZELTME: Ana sayfa ('/') artık 'dashboard'a SADECE YÖNLENDİRİR (isimsiz).
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// DÜZELTME: '/dashboard' rotası 'dashboard' ismini alan TEK ROTA oldu.
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// Breeze'in 'Profil' Rotaları (Bunlar doğruydu)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// --- HTS API ROTLARI ---
// (Bu kısım bir önceki adımdan, doğru ve tam)
Route::prefix('api')->middleware(['auth'])->group(function () {

    /**
     * 1. GoalController Rotaları (Sütun 1-5)
     */
    Route::controller(GoalController::class)->group(function () {
        Route::get('/goal-categories', 'getGoalCategories');
        Route::post('/goal-categories', 'storeCategory');
        Route::put('/goal-categories/toggle/{goalCategory}', 'toggleCategory');
        Route::delete('/goal-categories/{goalCategory}', 'destroyCategory');
        Route::put('/goal-categories/{goalCategory}', 'updateCategory');
        Route::get('/annual-goals/{goalCategory}', 'getAnnualGoals');
        Route::post('/annual-goals', 'storeAnnualGoal'); 
        Route::put('/annual-goals/toggle/{annualGoal}', 'toggleAnnualGoal');
        Route::delete('/annual-goals/{annualGoal}', 'destroyAnnualGoal');
        Route::put('/annual-goals/{annualGoal}', 'updateAnnualGoal');
        Route::get('/monthly-goals/{annualGoal}', 'getMonthlyGoals');
        Route::post('/monthly-goals', 'storeMonthlyGoal');
        Route::put('/monthly-goals/toggle/{monthlyGoal}', 'toggleMonthlyGoal');
        Route::delete('/monthly-goals/{monthlyGoal}', 'destroyMonthlyGoal');
        Route::put('/monthly-goals/{monthlyGoal}', 'updateMonthlyGoal');
        Route::get('/weekly-goals/{monthlyGoal}', 'getWeeklyGoals');
        Route::post('/weekly-goals', 'storeWeeklyGoal');
        Route::put('/weekly-goals/toggle/{weeklyGoal}', 'toggleWeeklyGoal');
        Route::delete('/weekly-goals/{weeklyGoal}', 'destroyWeeklyGoal');
        Route::put('/weekly-goals/{weeklyGoal}', 'updateWeeklyGoal');
        Route::get('/daily-goals/{weeklyGoal}', 'getDailyGoals');
        Route::post('/daily-goals', 'storeDailyGoal');
        Route::put('/daily-goals/toggle/{dailyGoal}', 'toggleDailyGoal');
        Route::delete('/daily-goals/{dailyGoal}', 'destroyDailyGoal');
        Route::put('/daily-goals/{dailyGoal}', 'updateDailyGoal');
        Route::get('/tasks/{dailyGoal}', 'getTasks');
    });

    /**
     * 2. Görev (Task) Yönetim Rotaları (TaskController - Sütun 6)
     */
    Route::controller(TaskController::class)->group(function () {
        Route::post('/tasks', 'store');
        Route::put('/tasks/toggle/{task}', 'toggle');
        Route::delete('/tasks/{task}', 'destroyTask');
        Route::put('/tasks/{task}', 'updateTask');
    });

    /**
     * 3. Sıralama (Reordering) Rotası
     */
    Route::put('/reorder', [ReorderController::class, 'updateOrder']);

}); // <-- /api ve auth grubunun sonu


// Breeze'in oluşturduğu kimlik doğrulama rotalarını yükle
// (Bu /login, /register, /logout vb. içerir)
require __DIR__.'/auth.php';