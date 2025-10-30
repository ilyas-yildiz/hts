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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GoalController extends Controller
{
    /**
     * Varsayılan kullanıcıyı bulur veya (eğer veritabanı boşsa) oluşturur.
     */
    private function getOrCreateDefaultUser(): User
    {
        return User::firstOrCreate(
            ['email' => 'mail@adresiniz.com'],
            ['name' => 'İlyas Yıldız', 'password' => Hash::make('password')]
        );
    }

    // --- GET METODLARI (Veri Çekme) ---
    public function getGoalCategories(): JsonResponse
    {
        $user = $this->getOrCreateDefaultUser();
        return response()->json($user->goalCategories);
    }
    public function getAnnualGoals(GoalCategory $goalCategory): JsonResponse
    {
        return response()->json($goalCategory->annualGoals);
    }
    public function getMonthlyGoals(AnnualGoal $annualGoal): JsonResponse
    {
        return response()->json($annualGoal->monthlyGoals);
    }
    public function getWeeklyGoals(MonthlyGoal $monthlyGoal): JsonResponse
    {
        return response()->json($monthlyGoal->weeklyGoals);
    }
    public function getDailyGoals(WeeklyGoal $weeklyGoal): JsonResponse
    {
        return response()->json($weeklyGoal->dailyGoals);
    }
    public function getTasks(DailyGoal $dailyGoal): JsonResponse
    {
        $tasks = $dailyGoal->tasks()
                          ->orderBy('is_completed', 'asc')
                          ->orderBy('created_at', 'asc')
                          ->get();
                          
        return response()->json($tasks);
    }


    // --- STORE METODLARI (Yeni Ekleme) ---
    public function storeCategory(Request $request): JsonResponse
    {
        $validated = $request->validate(['name' => 'required|string|max:255']);
        $user = $this->getOrCreateDefaultUser();
        $category = $user->goalCategories()->create(['name' => $validated['name']]);
        return response()->json($category, 201);
    }
    public function storeAnnualGoal(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'goal_category_id' => 'required|exists:goal_categories,id',
            'title' => 'required|string|max:255',
            'year' => 'required|integer|min:1|max:5',
            'period_label' => 'required|string|max:255',
        ]);
        $annualGoal = AnnualGoal::create($validated);
        return response()->json($annualGoal, 201);
    }
    public function storeMonthlyGoal(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'annual_goal_id' => 'required|exists:annual_goals,id',
            'title' => 'required|string|max:255',
            'month_label' => 'required|string|max:255',
        ]);
        $monthlyGoal = MonthlyGoal::create($validated);
        return response()->json($monthlyGoal, 201);
    }
    public function storeWeeklyGoal(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'monthly_goal_id' => 'required|exists:monthly_goals,id',
            'title' => 'required|string|max:255',
            'week_label' => 'required|string|max:255',
        ]);
        $weeklyGoal = WeeklyGoal::create($validated);
        return response()->json($weeklyGoal, 201);
    }
    public function storeDailyGoal(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'weekly_goal_id' => 'required|exists:weekly_goals,id',
            'day_label' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
        ]);
        $dailyGoal = DailyGoal::create($validated);
        return response()->json($dailyGoal, 201);
    }


    // --- TOGGLE METODLARI (Tamamlandı İşaretleme) ---
    public function toggleCategory(Request $request, GoalCategory $goalCategory): JsonResponse
    {
        $validated = $request->validate(['is_completed' => 'required|boolean']);
        $goalCategory->update(['is_completed' => $validated['is_completed']]);
        return response()->json($goalCategory);
    }
    public function toggleAnnualGoal(Request $request, AnnualGoal $annualGoal): JsonResponse
    {
        $validated = $request->validate(['is_completed' => 'required|boolean']);
        $annualGoal->update(['is_completed' => $validated['is_completed']]);
        return response()->json($annualGoal);
    }
    public function toggleMonthlyGoal(Request $request, MonthlyGoal $monthlyGoal): JsonResponse
    {
        $validated = $request->validate(['is_completed' => 'required|boolean']);
        $monthlyGoal->update(['is_completed' => $validated['is_completed']]);
        return response()->json($monthlyGoal);
    }
    public function toggleWeeklyGoal(Request $request, WeeklyGoal $weeklyGoal): JsonResponse
    {
        $validated = $request->validate(['is_completed' => 'required|boolean']);
        $weeklyGoal->update(['is_completed' => $validated['is_completed']]);
        return response()->json($weeklyGoal);
    }
    public function toggleDailyGoal(Request $request, DailyGoal $dailyGoal): JsonResponse
    {
        $validated = $request->validate(['is_completed' => 'required|boolean']);
        $dailyGoal->update(['is_completed' => $validated['is_completed']]);
        return response()->json($dailyGoal);
    }


    // --- DESTROY METODLARI (Silme) ---
    public function destroyCategory(GoalCategory $goalCategory): JsonResponse
    {
        $goalCategory->delete();
        return response()->json(null, 204); 
    }
    public function destroyAnnualGoal(AnnualGoal $annualGoal): JsonResponse
    {
        $annualGoal->delete();
        return response()->json(null, 204);
    }
    public function destroyMonthlyGoal(MonthlyGoal $monthlyGoal): JsonResponse
    {
        $monthlyGoal->delete();
        return response()->json(null, 204);
    }
    public function destroyWeeklyGoal(WeeklyGoal $weeklyGoal): JsonResponse
    {
        $weeklyGoal->delete();
        return response()->json(null, 204);
    }
    public function destroyDailyGoal(DailyGoal $dailyGoal): JsonResponse
    {
        $dailyGoal->delete();
        return response()->json(null, 204);
    }


    // --- YENİ UPDATE METODLARI (Düzenleme) ---

    /**
     * Kategori (Sütun 1) günceller.
     */
    public function updateCategory(Request $request, GoalCategory $goalCategory): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $goalCategory->update($validated);
        return response()->json($goalCategory);
    }

    /**
     * Yıllık Hedef (Sütun 2) günceller.
     */
    public function updateAnnualGoal(Request $request, AnnualGoal $annualGoal): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'year' => 'required|integer|min:1|max:5',
            'period_label' => 'required|string|max:255',
        ]);
        $annualGoal->update($validated);
        return response()->json($annualGoal);
    }

    /**
     * Aylık Hedef (Sütun 3) günceller.
     */
    public function updateMonthlyGoal(Request $request, MonthlyGoal $monthlyGoal): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'month_label' => 'required|string|max:255',
        ]);
        $monthlyGoal->update($validated);
        return response()->json($monthlyGoal);
    }

    /**
     * Haftalık Hedef (Sütun 4) günceller.
     */
    public function updateWeeklyGoal(Request $request, WeeklyGoal $weeklyGoal): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'week_label' => 'required|string|max:255',
        ]);
        $weeklyGoal->update($validated);
        return response()->json($weeklyGoal);
    }

    /**
     * Günlük Hedef (Sütun 5) günceller.
     */
    public function updateDailyGoal(Request $request, DailyGoal $dailyGoal): JsonResponse
    {
        $validated = $request->validate([
            'day_label' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
        ]);
        $dailyGoal->update($validated);
        return response()->json($dailyGoal);
    }
}