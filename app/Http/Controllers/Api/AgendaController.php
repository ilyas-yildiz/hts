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

        // 1. Kullanıcının sahip olduğu tüm Kategori ID'lerini al
        $userCategoryIds = $user->goalCategories()->pluck('id');

        // 2. Görevleri (Tasks) bul:
        //    Tarihi = Parametreden Gelen Tarih ($date) OLAN
        $tasks = Task::where('goal_date', $date) 
                     ->whereIn('goal_category_id', $userCategoryIds)
                     ->with('goalCategory:id,name') 
                     ->orderBy('is_completed', 'asc')
                     ->orderBy('start_time', 'asc') 
                     ->orderBy('order_index', 'asc')
                     ->get();
                         
        return response()->json($tasks);
    }
}