<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskListRequest;
use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param TaskListRequest $request
     * @return JsonResponse
     */
    public function index(TaskListRequest $request): JsonResponse
    {
        try {
            $per_page = $request->query('per_page', 15);

            $tasks = Task::query()
                ->when(
                    !empty($request->filled('term')),
                    function (Builder $query) use ($request): void {
                        $query
                            ->where('name', 'like', '%' . $request->input('term') . '%');
                    },
                )
                ->withOnly('user')
                ->orderBy('id')
                ->paginate($per_page);
        } catch (Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ],
            Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return response()->json([
            'results' => $tasks,
            'pagination' => [
                'more' => $tasks->hasMorePages(),
            ],
        ]);
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
        // need to add description field to tasks, create new validation request, and save assigned to too
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
     * @return JsonResponse
     */
    public function update(Task $task): JsonResponse
    {
        return response()->json([
            'message' => 'Your requested data is : ',
        ]); // need to create form to update name, description and assigned user
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function destroy(Task $task): JsonResponse
    {
        try {
            $task->delete();
        } catch (Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return response()->json([
            'message' => 'Task successfully deleted',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function changeStatus(Task $task): JsonResponse
    {
        try {
            $status = $task->getCompleteStatus();
            $status ? $task->unSetComplete() : $task->setComplete();
        } catch (Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return response()->json([
            'message' => 'Task status successfully changed',
        ]);
    }
}
