<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::take(500)->with('assignedUser')->get();
        return new TaskCollection($tasks);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $task = Task::create($request->all());
        $this->manageSpendTimeAdd($request, $task);
        return new TaskResource($task);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        if (null === $task->completed_at && $request->query->has("complete")) {
            $task->update([
                'completed_at' => now()
            ]);
            return new TaskResource($task);
        }
        $task->updateOrFail($request->all());
        $this->manageSpendTimeAdd($request, $task);

        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response(null, 204);
    }

    private function manageSpendTimeAdd(StoreTaskRequest $request, Task $task) : void
    {
        if (!$request->has('spent_time_add')) {
            return;
        }
        $add = intval($request->get('spent_time_add'));
        if ($add === 0) {
            return;
        }
        $task->increment('spent_time', $add);
        $task->update([
            'updated_at' => now()
        ]);
    }
}
