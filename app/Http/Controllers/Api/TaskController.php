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
        
        // DÜZENLENDİ: Yeni sıra numarasını hesapla
        $maxOrder = Task::where('daily_goal_id', $validated['daily_goal_id'])->max('order_index');
        $validated['order_index'] = $maxOrder + 1;
        
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

    // --- UPDATE (Değişiklik yok) ---
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