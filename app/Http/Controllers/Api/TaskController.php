<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Yeni bir görev (task) oluşturur.
     * Bu metot, welcome.blade.php'deki "Yeni Görev Ekle" formu tarafından çağrılır.
     */
    public function store(Request $request)
    {
        // Gelen veriyi doğrula
        $validatedData = $request->validate([
            'daily_goal_id' => 'required|exists:daily_goals,id',
            'task_description' => 'required|string|max:255',
            'time_label' => 'nullable|string|max:100',
        ]);

        // Doğrulanmış veriyle yeni görevi oluştur
        $task = Task::create($validatedData);

        // Oluşturulan görevi 201 (Created) koduyla JSON olarak döndür
        return response()->json($task, 201);
    }

    /**
     * Bir görevin 'is_completed' durumunu günceller.
     * Bu metot, welcome.blade.php'deki checkbox'a tıklandığında çağrılır.
     * * Laravel'in "Route Model Binding" özelliği sayesinde, URL'deki {task}
     * ID'sine sahip Task modelini otomatik olarak bulur ve $task değişkenine atar.
     */
    public function toggle(Request $request, Task $task)
    {
        // Gelen veriyi doğrula
        $validatedData = $request->validate([
            'is_completed' => 'required|boolean',
        ]);

        // Görevin durumunu güncelle
        $task->is_completed = $validatedData['is_completed'];
        $task->save();

        // Güncellenmiş görevi JSON olarak döndür
        return response()->json($task);
    }
}
