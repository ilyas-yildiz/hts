<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon; // GÜNCELLEME: Saat formatlama için eklendi

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
            'goal_date'        => 'required|date_format:Y-m-d', 
            'start_time'       => 'nullable|date_format:H:i',
            'end_time'         => 'nullable|date_format:H:i|after:start_time',
            'task_description' => 'required|string', 
        ]);

        $startTime = $validated['start_time'] ?? null;
        $endTime = $validated['end_time'] ?? null;
        $goalDate = $validated['goal_date'];

        // 2. ÇAKIŞMA KONTROLÜ
        if ($startTime) {
            
            $baseQuery = Task::where('goal_date', $goalDate)
                             ->whereNotNull('start_time');

            $conflictQuery = $baseQuery->where(function ($query) use ($startTime, $endTime) {

                if ($endTime) {
                    $query->where(function ($q) use ($startTime, $endTime) {
                        $q->whereNotNull('end_time')
                          ->where('start_time', '<', $endTime)
                          ->where('end_time', '>', $startTime);
                    })->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->whereNull('end_time')
                          ->where('start_time', '>=', $startTime)
                          ->where('start_time', '<', $endTime);
                    });
                } 
                else {
                    $query->where(function ($q) use ($startTime) {
                        $q->whereNotNull('end_time')
                          ->where('start_time', '<=', $startTime)
                          ->where('end_time', '>', $startTime);
                    })->orWhere(function ($q) use ($startTime) {
                        $q->whereNull('end_time')
                          ->where('start_time', '=', $startTime);
                    });
                }
            });

            $conflictingTask = $conflictQuery->first();

            if ($conflictingTask) {
                // GÜNCELLEME: Saatleri 'H:i' (10:00) formatına çevir
                $st = Carbon::parse($conflictingTask->start_time)->format('H:i');
                $et = $conflictingTask->end_time ? Carbon::parse($conflictingTask->end_time)->format('H:i') : null;
                
                $conflictTimeStr = $et ? $st . ' - ' . $et : $st;

                $newTimeStr = $endTime ? "$startTime - $endTime" : $startTime;

                // GÜNCELLEME: Başlıktaki "Çakışma:" kelimesi kaldırıldı
                throw ValidationException::withMessages([
                    'time' => [
                        "Girmek istediğiniz saat ($newTimeStr),",
                        "mevcut '$conflictTimeStr - {$conflictingTask->task_description}' görevi ile çakışıyor."
                    ],
                ]);
            }
        }
        
        // 3. Sıralamayı hesapla
        $maxOrder = Task::where('goal_category_id', $validated['goal_category_id'])
                            ->where('goal_date', $validated['goal_date'])
                            ->max('order_index');
                            
        $validated['order_index'] = ($maxOrder ?? 0) + 1;
        
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
            'goal_category_id' => 'required|exists:goal_categories,id', 
            'goal_date'        => 'nullable|date_format:Y-m-d',
            'start_time'       => 'nullable|date_format:H:i',
            'end_time'         => 'nullable|date_format:H:i|after:start_time',
            'task_description' => 'required|string',
        ]);

        $startTime = $validated['start_time'] ?? $task->start_time;
        $endTime = $validated['end_time'] ?? $task->end_time;
        if ($request->has('end_time')) {
            $endTime = $validated['end_time'];
        }
        
        $goalDate = $validated['goal_date'] ?? $task->goal_date;

        // 2. GÜNCELLENMİŞ ÇAKIŞMA KONTROLÜ (Update için)
        if ($startTime) {
            
            $baseQuery = Task::where('goal_date', $goalDate)
                             ->where('id', '!=', $task->id) 
                             ->whereNotNull('start_time');

            $conflictQuery = $baseQuery->where(function ($query) use ($startTime, $endTime) {

                if ($endTime) {
                    $query->where(function ($q) use ($startTime, $endTime) {
                        $q->whereNotNull('end_time')
                          ->where('start_time', '<', $endTime)
                          ->where('end_time', '>', $startTime);
                    })->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->whereNull('end_time')
                          ->where('start_time', '>=', $startTime)
                          ->where('start_time', '<', $endTime);
                    });
                } 
                else {
                    $query->where(function ($q) use ($startTime) {
                        $q->whereNotNull('end_time')
                          ->where('start_time', '<=', $startTime)
                          ->where('end_time', '>', $startTime);
                    })->orWhere(function ($q) use ($startTime) {
                        $q->whereNull('end_time')
                          ->where('start_time', '=', $startTime);
                    });
                }
            });

            $conflictingTask = $conflictQuery->first();

            if ($conflictingTask) {
                // GÜNCELLEME: Saatleri 'H:i' (10:00) formatına çevir
                $st = Carbon::parse($conflictingTask->start_time)->format('H:i');
                $et = $conflictingTask->end_time ? Carbon::parse($conflictingTask->end_time)->format('H:i') : null;

                $conflictTimeStr = $et ? $st . ' - ' . $et : $st;

                $newTimeStr = $endTime ? "$startTime - $endTime" : $startTime;

                // GÜNCELLEME: Başlıktaki "Çakışma:" kelimesi kaldırıldı
                throw ValidationException::withMessages([
                    'time' => [
                        "Girmek istediğiniz saat ($newTimeStr),",
                        "mevcut '$conflictTimeStr - {$conflictingTask->task_description}' görevi ile çakışıyor."
                    ],
                ]);
            }
        }
        
        // 3. Güncelle
        if ($request->has('start_time')) {
             $validated['start_time'] = $startTime;
        }
        if ($request->has('end_time')) {
             $validated['end_time'] = $endTime;
        }

        $task->update($validated);

        return response()->json($task);
    }
}