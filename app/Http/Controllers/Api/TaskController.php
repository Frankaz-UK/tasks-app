<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskListRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\TaskUserRequest;
use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
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
                ->when(
                    !empty($request->filled('user')),
                    function (Builder $query) use ($request): void {
                        $query
                            ->where('user_id', '=', $request->input('user'));
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
     * @param TaskRequest $request
     * @param Task $task
     * @return JsonResponse
     */
    public function store(TaskRequest $request, Task $task): JsonResponse
    {
        try {
            $task->name = $request->input('name');
            $task->description = $request->input('description');
            $task->user_id = $request->input('user_id');
            $task->save();
        } catch (Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return response()->json([
            'message' => 'Task successfully added',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Task $task
     * @param TaskRequest $request
     * @return JsonResponse
     */
    public function update(Task $task, TaskRequest $request): JsonResponse
    {
        try {
            $task->name = $request->input('name');
            $task->description = $request->input('description');
            $task->user_id = $request->input('user_id');
            $task->save();
        } catch (Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return response()->json([
            'message' => 'Task successfully updated',
        ]);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function changeUser(Task $task, TaskUserRequest $request): JsonResponse
    {
        try {
            $task->user_id = $request->input('user_id');
            $task->save();
        } catch (Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return response()->json([
            'message' => 'Task user successfully changed',
        ]);
    }
}
