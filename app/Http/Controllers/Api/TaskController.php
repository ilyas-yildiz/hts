<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException; // Hata fırlatmak için eklendi

class TaskController extends Controller
{
    /**
     * DÜZENLENDİ: Yeni bir görev (Sütun 6) V3 mantığına (Çakışma Kontrolü) göre oluşturur.
     */
    public function store(Request $request): JsonResponse
    {
        // 1. Gelen veriyi doğrula
        $validated = $request->validate([
            'goal_category_id' => 'required|exists:goal_categories,id',
            'goal_date'        => 'required|date',
            'start_time'       => 'nullable|date_format:H:i', // '14:30' formatı
            'end_time'         => 'nullable|date_format:H:i|after:start_time', // Bitiş, başlangıçtan sonra olmalı
            'task_description' => 'required|string|max:1000',
        ]);

        // 2. YENİ: Çakışma Kontrolü (Conflict Check)
        // Sadece 'start_time' girildiyse (Tüm gün görevi değilse) kontrol et
        if ($validated['start_time'] && $validated['end_time']) {
            $startTime = $validated['start_time'];
            $endTime = $validated['end_time'];

            // Veritabanında, o gün için, bu saat aralığıyla çakışan başka bir görev var mı?
            $conflictingTask = Task::where('goal_date', $validated['goal_date'])
                // Kendi ID'si dışındakilere bak (update için)
                // ->where('id', '!=', $task->id ?? 0) 
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->where(function ($q) use ($startTime, $endTime) {
                        // Yeni görev, mevcut görevin *içinde* mi başlıyor?
                        $q->where('start_time', '<', $endTime)
                          ->where('end_time', '>', $startTime);
                    });
                })
                ->exists(); // Sadece var mı diye bak, getirme

            if ($conflictingTask) {
                // Eğer çakışma varsa, 422 (Unprocessable Entity) hatası döndür
                throw ValidationException::withMessages([
                    'time' => 'Bu saat aralığı ('.$startTime.' - '.$endTime.') zaten dolu. Lütfen başka bir saat seçin.',
                ]);
            }
        }
        
        // 3. Sıralamayı hesapla
        $maxOrder = Task::where('goal_category_id', $validated['goal_category_id'])
                        ->where('goal_date', $validated['goal_date'])
                        ->max('order_index');
                        
        $validated['order_index'] = $maxOrder + 1;
        
        // 4. Kaydet
        $task = Task::create($validated);
        return response()->json($task, 201);
    }

    // --- TOGGLE (Değişiklik yok) ---
    public function toggle(Request $request, Task $task): JsonResponse
    {
        $validated = $request->validate([
            'is_completed' => 'required|boolean',
        ]);
        $task->update($validated);
        return response()->json($task);
    }

    // --- DESTROY (Değişiklik yok) ---
    public function destroyTask(Task $task): JsonResponse
    {
        $task->delete();
        return response()->json(null, 204);
    }

    /**
     * DÜZENLENDİ: Bir görevin (Sütun 6) içeriğini V3 mantığına göre günceller.
     */
    public function updateTask(Request $request, Task $task): JsonResponse
    {
        // 1. Gelen veriyi doğrula
        $validated = $request->validate([
            'start_time'       => 'nullable|date_format:H:i',
            'end_time'         => 'nullable|date_format:H:i|after:start_time',
            'task_description' => 'required|string|max:1000',
            'goal_date'        => 'nullable|date',
        ]);

        // 2. YENİ: Çakışma Kontrolü (Update için)
        if (isset($validated['start_time']) && isset($validated['end_time'])) {
            $startTime = $validated['start_time'];
            $endTime = $validated['end_time'];
            $goalDate = $validated['goal_date'] ?? $task->goal_date; // Tarih değişmiyorsa eskisi

            $conflictingTask = Task::where('goal_date', $goalDate)
                // KENDİSİ HARİÇ (Update'in en önemli kuralı)
                ->where('id', '!=', $task->id) 
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->where(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<', $endTime)
                          ->where('end_time', '>', $startTime);
                    });
                })
                ->exists();

            if ($conflictingTask) {
                throw ValidationException::withMessages([
                    'time' => 'Bu saat aralığı ('.$startTime.' - '.$endTime.') zaten dolu.',
                ]);
            }
        }
        
        // 3. Güncelle
        $task->update($validated);

        return response()->json($task);
    }
}