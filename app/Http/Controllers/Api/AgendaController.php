<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
// use Illuminate\Http\Request; // (Kullanılmıyor)
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use Carbon\Carbon; 

class AgendaController extends Controller
{
    /**
     * Belirli bir tarihin ajandasını getirir.
     * @param string $date ('YYYY-MM-DD' formatında gelir)
     */
 public function getAgendaForDate(string $date): JsonResponse
    {
        $user = Auth::user();
        $userCategoryIds = $user->goalCategories()->pluck('id');

        $tasks = Task::where('goal_date', $date) 
                     ->whereIn('goal_category_id', $userCategoryIds)
                     // GÜNCELLEME: Tüm üst hedefleri de getir
                     ->with([
                         'goalCategory:id,name',
                         'annualGoal:id,title',
                         'monthlyGoal:id,title',
                         'weeklyGoal:id,title',
                         'dailyGoal:id,title,day_label'
                     ])
                     // ------------------------------------
                     ->orderBy('is_completed', 'asc')
                     ->orderBy('start_time', 'asc') 
                     ->orderBy('order_index', 'asc')
                     ->get();
                         
        return response()->json($tasks);
    }
}