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

class GoalController extends Controller
{
    // ... (getGoalCategories, storeCategory, getAnnualGoals - aynı) ...
    public function getGoalCategories(): JsonResponse
    {
        $user = User::first();
        if (!$user) {
            return response()->json([]);
        }
        return response()->json($user->goalCategories);
    }
    public function storeCategory(Request $request): JsonResponse
    {
        $validated = $request->validate(['name' => 'required|string|max:255']);
        $user = User::first();
        if (!$user) {
            return response()->json(['message' => 'Kullanıcı bulunamadı.'], 404);
        }
        $category = $user->goalCategories()->create(['name' => $validated['name']]);
        return response()->json($category, 201);
    }
    public function getAnnualGoals(GoalCategory $goalCategory): JsonResponse
    {
        return response()->json($goalCategory->annualGoals);
    }

    // ... (storeAnnualGoal - aynı) ...
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


    // ... (getMonthlyGoals - aynı) ...
    public function getMonthlyGoals(AnnualGoal $annualGoal): JsonResponse
    {
        return response()->json($annualGoal->monthlyGoals);
    }

    /**
     * YENİ METOD: 3. Kolon için yeni Aylık Hedef oluşturur.
     */
    public function storeMonthlyGoal(Request $request): JsonResponse
    {
        // 1. Gelen veriyi doğrula
        $validated = $request->validate([
            'annual_goal_id' => 'required|exists:annual_goals,id', // Bir üstteki Yıllık Hedefe bağlı olmalı
            'title' => 'required|string|max:255',
            'month_label' => 'required|string|max:255', // örn: "Ekim 2025"
        ]);

        // 2. Yeni Aylık Hedefi oluştur
        $monthlyGoal = MonthlyGoal::create($validated);

        // 3. Başarılı olduysa, oluşturulan yeni hedefi 201 koduyla döndür
        return response()->json($monthlyGoal, 201);
    }


    // ... (Kalan getWeeklyGoals, getDailyGoals, getTasks metodları - aynı) ...
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
}