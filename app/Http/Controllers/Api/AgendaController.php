<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use Carbon\Carbon; // Tarih işlemleri için

class AgendaController extends Controller
{
    /**
     * "Bugünün Ajandası"nı, yani giriş yapmış kullanıcının
     * tüm kategorilerindeki bugünkü görevlerini getirir.
     */
    public function getTodayAgenda(): JsonResponse
    {
        $user = Auth::user();

        // 1. Kullanıcının sahip olduğu tüm Kategori ID'lerini al
        $userCategoryIds = $user->goalCategories()->pluck('id');

        // 2. Bugünün tarihini al (sunucunun saat dilimine göre)
        $today = Carbon::today()->toDateString(); // '2025-11-07'

        // 3. Görevleri (Tasks) bul:
        //    Kategorisi = Kullanıcının Kategorilerinden Biri OLAN
        //    VE Tarihi = Bugün OLAN
        $tasks = Task::where('goal_date', $today)
                      ->whereIn('goal_category_id', $userCategoryIds)
                      ->with('goalCategory:id,name') // Görevin hangi projeye ait olduğunu da getir
                      ->orderBy('is_completed', 'asc')
                      ->orderBy('start_time', 'asc') // Saati boş olanlar (Tüm Gün) en üste gelir
                      ->orderBy('order_index', 'asc')
                      ->get();
                          
        return response()->json($tasks);
    }
}