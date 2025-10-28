<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AnnualGoal;
use App\Models\DailyGoal;
use App\Models\GoalCategory;
use App\Models\MonthlyGoal;
use App\Models\Task;
use App\Models\User;
use App\Models\WeeklyGoal;
use Illuminate\Http\JsonResponse; // JsonResponse'u ekledik
use Illuminate\Http\Request;

class GoalController extends Controller
{
    /**
     * 1. Kolon: Ana Kategorileri getirir.
     * "Sadece kendim kullanacağım" dediğiniz için, sistemi
     * 'users' tablosundaki ilk kullanıcıya göre çalışacak şekilde ayarlıyoruz.
     */
    public function getGoalCategories(): JsonResponse
    {
        // users tablosundaki ilk kullanıcıyı bul.
        $user = User::first();

        // Eğer hiç kullanıcı yoksa, boş bir dizi döndür.
        if (!$user) {
            return response()->json([]);
        }

        // Kullanıcının kategorilerini döndür.
        return response()->json($user->goalCategories);
    }

    /**
     * 2. Kolon: Seçili kategoriye ait Yıllık Hedefleri getirir.
     * Laravel'in "Route Model Binding" özelliği sayesinde
     * URL'deki {goalCategory} ID'si otomatik olarak modeli bulur.
     */
    public function getAnnualGoals(GoalCategory $goalCategory): JsonResponse
    {
        // Modele bağlı 'annualGoals' ilişkisini (Model'de tanımladık) çağırıyoruz.
        return response()->json($goalCategory->annualGoals);
    }

    /**
     * 3. Kolon: Seçili yıllık hedefe ait Aylık Hedefleri getirir.
     */
    public function getMonthlyGoals(AnnualGoal $annualGoal): JsonResponse
    {
        return response()->json($annualGoal->monthlyGoals);
    }

    /**
     * 4. Kolon: Seçili aylık hedefe ait Haftalık Hedefleri getirir.
     */
    public function getWeeklyGoals(MonthlyGoal $monthlyGoal): JsonResponse
    {
        return response()->json($monthlyGoal->weeklyGoals);
    }

    /**
     * 5. Kolon: Seçili haftalık hedefe ait Günlük Hedefleri getirir.
     */
    public function getDailyGoals(WeeklyGoal $weeklyGoal): JsonResponse
    {
        return response()->json($weeklyGoal->dailyGoals);
    }

    /**
     * 6. Kolon: Seçili güne ait Görevleri (Task) getirir.
     */
    public function getTasks(DailyGoal $dailyGoal): JsonResponse
    {
        // Görevleri, tamamlanma durumuna (önce tamamlanmamışlar)
        // ve oluşturulma zamanına göre sıralı getirelim.
        $tasks = $dailyGoal->tasks()
                          ->orderBy('is_completed', 'asc')
                          ->orderBy('created_at', 'asc')
                          ->get();
                          
        return response()->json($tasks);
    }
}
