<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(): JsonResponse
    {
        $tasks = Task::all();

        return response()->json(['data' => $tasks]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $payload = $request->only('name', 'description');
        $task = Task::create($payload);

        return response()->json($task, 201);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param int|string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $payload = $request->only('name', 'description', 'is_done');
        $task = Task::find($id);
        $task->update($payload);

        return response()->json($task);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param int|string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        Task::find($id)->delete();

        return response()->json([], 204);
    }
}
