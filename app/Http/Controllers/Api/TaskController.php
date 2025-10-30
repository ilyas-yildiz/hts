<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse; // JsonResponse eklendi

class TaskController extends Controller
{
    /**
     * Yeni bir görev (Sütun 6) oluşturur.
     */
    public function store(Request $request): JsonResponse
    {
        // 'task_description' için max validation artırıldı
        $validated = $request->validate([
            'daily_goal_id' => 'required|exists:daily_goals,id',
            'time_label' => 'nullable|string|max:255',
            'task_description' => 'required|string|max:1000', 
        ]);

        $task = Task::create($validated);

        return response()->json($task, 201); // 201: Kaynak oluşturuldu
    }

    /**
     * Bir görevin (Sütun 6) tamamlanma durumunu günceller.
     */
    public function toggle(Request $request, Task $task): JsonResponse
    {
        $validated = $request->validate([
            'is_completed' => 'required|boolean',
        ]);

        $task->update([
            'is_completed' => $validated['is_completed']
        ]);

        return response()->json($task);
    }

    /**
     * YENİ METOD: Bir görevi (Sütun 6) siler.
     */
    public function destroyTask(Task $task): JsonResponse
    {
        $task->delete();
        // 204 (No Content) kodu, "Başarılı, yanıt olarak gönderecek bir şey yok" demektir.
        return response()->json(null, 204);
    }
}