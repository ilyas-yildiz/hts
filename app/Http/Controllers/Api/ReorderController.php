<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB; // Veritabanı işlemi (transaction) için

// Sıralama yapacağımız tüm Modelleri (beyaz liste için) dahil et
use App\Models\GoalCategory;
use App\Models\AnnualGoal;
use App\Models\MonthlyGoal;
use App\Models\WeeklyGoal;
use App\Models\DailyGoal;
use App\Models\Task;

class ReorderController extends Controller
{
    /**
     * Sürükle-bırak sonrası öğelerin yeni sırasını günceller.
     */
    public function updateOrder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'model_type' => 'required|string', // örn: 'GoalCategory'
            'ids'         => 'required|array',  // örn: [3, 1, 2]
            'ids.*'       => 'integer'          // Dizideki her eleman integer olmalı
        ]);

        // 1. Güvenlik: Gelen 'model_type' dizesini, izin verdiğimiz Model sınıflarıyla
        // eşleştiren bir "beyaz liste" (whitelist) oluştur.
        $modelMap = [
            'GoalCategory' => GoalCategory::class,
            'AnnualGoal'   => AnnualGoal::class,
            'MonthlyGoal'  => MonthlyGoal::class,
            'WeeklyGoal'   => WeeklyGoal::class,
            'DailyGoal'    => DailyGoal::class,
            'Task'         => Task::class,
        ];

        $modelType = $validated['model_type'];

        // Eğer gelen tip bizim listemizde yoksa, 403 (Yasak) hatası ver.
        if (!isset($modelMap[$modelType])) {
            return response()->json(['message' => 'Geçersiz model tipi.'], 403);
        }

        $modelClass = $modelMap[$modelType];
        $ids = $validated['ids'];

        // 2. Veritabanını Güncelle
        // Gelen ID dizisi [3, 1, 2] ise:
        // ID=3 olana order_index = 0
        // ID=1 olana order_index = 1
        // ID=2 olana order_index = 2
        // ataması yap.
        DB::transaction(function () use ($modelClass, $ids) {
            foreach ($ids as $index => $id) {
                // (Burada Auth (kullanıcı) kontrolü de yapılabilir, 
                // ancak tek kullanıcılı sistemde buna gerek yok)
                $modelClass::where('id', $id)
                            ->update(['order_index' => $index]);
            }
        });

        // 200 (OK) yanıtı döndür
        return response()->json(['message' => 'Sıralama güncellendi.']);
    }
}

