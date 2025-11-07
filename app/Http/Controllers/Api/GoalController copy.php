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
use Illuminate\Support\Facades\Auth; 

class GoalController extends Controller
{
    // ... (getOrCreateDefaultUser, tüm GET metodları, storeCategory, storeAnnualGoal, storeMonthlyGoal - aynı) ...

    public function getGoalCategories(): JsonResponse
    {
        // DÜZELTME: $user = User::first() yerine Auth::user()
        $user = Auth::user(); 
        
        return response()->json($user->goalCategories()->orderBy('order_index', 'asc')->get());
    }
    public function getAnnualGoals(GoalCategory $goalCategory): JsonResponse
    {
        // TODO: (İsteğe bağlı güvenlik) Bu kategorinin giriş yapan kullanıcıya ait olup olmadığını kontrol et
        return response()->json($goalCategory->annualGoals()->orderBy('order_index', 'asc')->get());
    }
    public function getMonthlyGoals(AnnualGoal $annualGoal): JsonResponse
    {
        return response()->json($annualGoal->monthlyGoals()->orderBy('order_index', 'asc')->get());
    }
    public function getWeeklyGoals(MonthlyGoal $monthlyGoal): JsonResponse
    {
        return response()->json($monthlyGoal->weeklyGoals()->orderBy('order_index', 'asc')->get());
    }
    public function getDailyGoals(WeeklyGoal $weeklyGoal): JsonResponse
    {
        return response()->json($weeklyGoal->dailyGoals()->orderBy('order_index', 'asc')->get());
    }
    public function getTasks(DailyGoal $dailyGoal): JsonResponse
    {
        $tasks = $dailyGoal->tasks()
                          ->orderBy('is_completed', 'asc')
                          ->orderBy('order_index', 'asc') 
                          ->get();
                          
        return response()->json($tasks);
    }


    // --- STORE METODLARI (max order_index eklendi) ---
    public function storeCategory(Request $request): JsonResponse
    {
        $validated = $request->validate(['name' => 'required|string|max:255']);
        
        // DÜZELTME: $user = $this->getOrCreateDefaultUser() yerine Auth::user()
        $user = Auth::user();

        $maxOrder = $user->goalCategories()->max('order_index');
        $validated['order_index'] = $maxOrder + 1;
        
        $category = $user->goalCategories()->create($validated);
        return response()->json($category, 201);
    }
    public function storeAnnualGoal(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'goal_category_id' => 'required|exists:goal_categories,id', // TODO: Bu ID'nin kullanıcıya ait olduğunu doğrula
            'title' => 'required|string|max:255',
            'year' => 'required|integer|min:1|max:5',
            'period_label' => 'required|string|max:255',
        ]);
        
        $maxOrder = AnnualGoal::where('goal_category_id', $validated['goal_category_id'])->max('order_index');
        $validated['order_index'] = $maxOrder + 1;

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
        
        $maxOrder = MonthlyGoal::where('annual_goal_id', $validated['annual_goal_id'])->max('order_index');
        $validated['order_index'] = $maxOrder + 1;
        
        $monthlyGoal = MonthlyGoal::create($validated);
        return response()->json($monthlyGoal, 201);
    }


    // --- BU METODU GÜNCELLE ---
    public function storeWeeklyGoal(Request $request): JsonResponse
    {
        // DÜZENLENDİ: 'start_date' eklendi
        $validated = $request->validate([
            'monthly_goal_id' => 'required|exists:monthly_goals,id',
            'title' => 'required|string|max:255',
            'week_label' => 'required|string|max:255',
            'start_date' => 'nullable|date', // YENİ EKLENDİ
        ]);
        
        $maxOrder = WeeklyGoal::where('monthly_goal_id', $validated['monthly_goal_id'])->max('order_index');
        $validated['order_index'] = $maxOrder + 1;
        
        $weeklyGoal = WeeklyGoal::create($validated);
        return response()->json($weeklyGoal, 201);
    }

    // --- BU METODU GÜNCELLE ---
    public function storeDailyGoal(Request $request): JsonResponse
    {
        // DÜZENLENDİ: 'goal_date' eklendi
        $validated = $request->validate([
            'weekly_goal_id' => 'required|exists:weekly_goals,id',
            'day_label' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'goal_date' => 'nullable|date', // YENİ EKLENDİ
        ]);
        
        $maxOrder = DailyGoal::where('weekly_goal_id', $validated['weekly_goal_id'])->max('order_index');
        $validated['order_index'] = $maxOrder + 1;
        
        $dailyGoal = DailyGoal::create($validated);
        return response()->json($dailyGoal, 201);
    }


    // ... (Tüm TOGGLE ve DESTROY metodları aynı) ...
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


    // --- DESTROY METODLARI ---
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


    // --- UPDATE METODLARI ---
    public function updateCategory(Request $request, GoalCategory $goalCategory): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $goalCategory->update($validated);
        return response()->json($goalCategory);
    }
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
    public function updateMonthlyGoal(Request $request, MonthlyGoal $monthlyGoal): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'month_label' => 'required|string|max:255',
        ]);
        $monthlyGoal->update($validated);
        return response()->json($monthlyGoal);
    }
    
    // --- BU METODU GÜNCELLE ---
    public function updateWeeklyGoal(Request $request, WeeklyGoal $weeklyGoal): JsonResponse
    {
        // DÜZENLENDİ: 'start_date' eklendi
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'week_label' => 'required|string|max:255',
            'start_date' => 'nullable|date', // YENİ EKLENDİ
        ]);
        $weeklyGoal->update($validated);
        return response()->json($weeklyGoal);
    }

    // --- BU METODU GÜNCELLE ---
    public function updateDailyGoal(Request $request, DailyGoal $dailyGoal): JsonResponse
    {
        // DÜZENLENDİ: 'goal_date' eklendi
        $validated = $request->validate([
            'day_label' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'goal_date' => 'nullable|date', // YENİ EKLENDİ
        ]);
        $dailyGoal->update($validated);
        return response()->json($dailyGoal);
    }
}