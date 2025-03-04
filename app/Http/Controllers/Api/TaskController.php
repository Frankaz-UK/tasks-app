<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskListRequest;
use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param TaskListRequest $request
     * @return array
     */
    public function index(TaskListRequest $request): array
    {
        $per_page = $request->query('per_page', 15);

        $tasks = Task::query()
            ->when(
                ! empty($request->filled('term')),
                function (Builder $query) use ($request): void {
                    $query
                        ->where('name', 'like', '%' . $request->input('term') . '%');
                },
            )
            ->orderBy('id')
            ->paginate($per_page);

        return [
            'results' => $tasks,
            'pagination' => [
                'more' => $tasks->hasMorePages(),
            ],
        ];
    }

    /**
     * Store a newly created resource in storage. (note: Task $task creates a new instance of Task model)
     *
     * @param Request $request
     * @param Task $task
     * @return array
     */
    public function store(Request $request, Task $task): array
    {
        $request->validate([
            'name' => [
                'string',
                'max:255',
                'min:15',
            ],
        ]);

        $task->name = $request->input('name');
        $task->save();
        return [];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Task $task
     * @return array
     */
    public function update(Task $task): array
    {
        $status = $task->getCompleteStatus();
        $status ? $task->unSetComplete() : $task->setComplete();
        return [];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Task $task
     * @return array
     */
    public function destroy(Task $task): array
    {
        $task->delete();
        return [];
    }
}
