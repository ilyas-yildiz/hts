<?php

use App\Http\Controllers\Api\GoalController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Bu dosya, uygulamanızın web arayüzü ve (bu özel kurulumda)
| API rotaları için merkezi noktadır.
|
*/

// --- WEB ROUTE ---
// Bu rota, http://localhost adresine gidildiğinde
// 'welcome.blade.php' dosyasını (yani arayüzümüzü) yükler.
Route::get('/', function () {
    return view('welcome');
});


// --- API ROUTES ---
// Arayüzdeki (welcome.blade.php) JavaScript, her zaman /api/ ön eki ile istek atıyor.
// Bu yüzden tüm API rotalarımızı bu `prefix('api')` grubu içine alıyoruz.
Route::prefix('api')->group(function () {

    /**
     * 1. Veri Getirme Rotaları (GoalController)
     */
    Route::controller(GoalController::class)->group(function () {
        // 1. Kolon: Ana Kategorileri (Kişisel Gelişim, Finansal vb.) getir
        Route::get('/goal-categories', 'getGoalCategories');

        // YENİ EKLEME ROTASI (Sütun 1 için)
        // Yeni bir ana kategori oluşturmak için POST isteği
        Route::post('/goal-categories', 'storeCategory');

        // 2. Kolon: Seçili kategoriye ait Yıllık Hedefleri getir
        Route::get('/annual-goals/{goalCategory}', 'getAnnualGoals');

        // 3. Kolon: Seçili yıllık hedefe ait Aylık Hedefleri getir
        Route::get('/monthly-goals/{annualGoal}', 'getMonthlyGoals');

        // 4. Kolon: Seçili aylık hedefe ait Haftalık Hedefleri getir
        Route::get('/weekly-goals/{monthlyGoal}', 'getWeeklyGoals');

        // 5. Kolon: Seçili haftalık hedefe ait Günlük Hedefleri (Pzt, Salı vb.) getir
        Route::get('/daily-goals/{weeklyGoal}', 'getDailyGoals');

        // 6. Kolon: Seçili güne ait Görevleri (Task) getir
        Route::get('/tasks/{dailyGoal}', 'getTasks');
    });

    /**
     * 2. Görev (Task) Yönetim Rotaları (TaskController)
     */
    Route::controller(TaskController::class)->group(function () {
        // "Yeni Görev Ekle" butonu (Bu doğruydu)
        Route::post('/tasks', 'store');

        // Checkbox'lar için (Arayüz /api/tasks/toggle/{task} çağırıyor)
        Route::put('/tasks/toggle/{task}', 'toggle');
    });
});