<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan; // Cache ve Migrate rotaları için

// HTS Kontrolcülerimizi buraya dahil et
use App\Http\Controllers\Api\GoalController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\ReorderController;
use App\Http\Controllers\Api\AgendaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- BREEZE WEB ARAYÜZ ROTLARI ---
Route::get('/', function () {
    return redirect()->route('dashboard');
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// --- HTS API ROTLARI ---
Route::prefix('api')->middleware(['auth'])->group(function () {

    // (Tüm GoalController, TaskController, ReorderController, AgendaController rotaların burada...
    // ... Bu kısımda değişiklik yok ...)
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
    Route::controller(TaskController::class)->group(function () {
        Route::post('/tasks', 'store');
        Route::put('/tasks/toggle/{task}', 'toggle');
        Route::delete('/tasks/{task}', 'destroyTask');
        Route::put('/tasks/{task}', 'updateTask');
    });
    Route::controller(ReorderController::class)->group(function () {
        Route::put('/reorder', 'updateOrder');
    });
    Route::controller(AgendaController::class)->group(function () {
        Route::get('/agenda/today', 'getTodayAgenda');
    });
});


// Breeze'in oluşturduğu kimlik doğrulama rotalarını yükle
require __DIR__.'/auth.php';


// --- SUNUCU YÖNETİM ROTLARI ---

// Cache temizleme rotası (Bu sende zaten vardı)
Route::get('/sistemi-temizle-12345', function () {
    try {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        Artisan::call('event:clear');
        return "Butun (AGRESİF) onbellekler temizlendi!";
    } catch (Exception $e) {
        return "Hata: " . $e->getMessage();
    }
});

// YENİ EKLENDİ: Veritabanı (Migration) çalıştırma rotası
// (Bunu 'sistemi-temizle' rotasının hemen altına ekledim)
Route::get('/veritabani-guncelle-v3-gizli', function () {
    try {
        // (Eğer 'production' ortamındaysa, '--force' gerekir)
        Artisan::call('migrate', ['--force' => true]); 
        return "Veritabani (Migrate) basariyla guncellendi!";
    } catch (Exception $e) {
        return "Hata: " . $e->getMessage();
    }
});