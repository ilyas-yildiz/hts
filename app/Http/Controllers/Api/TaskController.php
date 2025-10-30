<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    /**
     * Yeni bir görev (Sütun 6) oluşturur.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'daily_goal_id' => 'required|exists:daily_goals,id',
            'time_label' => 'nullable|string|max:255',
            'task_description' => 'required|string|max:1000',
        ]);
        $task = Task::create($validated);
        return response()->json($task, 201);
    }

    /**
     * Bir görevin (Sütun 6) tamamlanma durumunu günceller.
     */
    public function toggle(Request $request, Task $task): JsonResponse
    {
        $validated = $request->validate([
            'is_completed' => 'required|boolean',
        ]);
        $task->update($validated);
        return response()->json($task);
    }

    /**
     * Bir görevi (Sütun 6) siler.
     */
    public function destroyTask(Task $task): JsonResponse
    {
        $task->delete();
        return response()->json(null, 204);
    }

    /**
     * YENİ METOD: Bir görevin (Sütun 6) içeriğini günceller.
     */
    public function updateTask(Request $request, Task $task): JsonResponse
    {
        $validated = $request->validate([
            'time_label' => 'nullable|string|max:255',
            'task_description' => 'required|string|max:1000',
        ]);

        $task->update($validated);

        return response()->json($task);
    }
}