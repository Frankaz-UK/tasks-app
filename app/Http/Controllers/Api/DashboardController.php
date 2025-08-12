<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        try {
            $tasksPerUser =
                User::query()
                    ->select(['id', 'forename', 'surname'])
                    ->withCount('tasks')
                    ->withCount([
                        'tasks as completed_tasks_count' => function ($query) {
                            $query->where('complete', 1);
                        }])
                    ->orderBy('surname')
                    ->where('id', '!=', 1)
                    ->get();
            $tasksCount = [
                'tasks' => Task::query()
                    ->count(),
                'tasksCompleted' => Task::query()
                    ->where('complete', 1)
                    ->count(),
                'tasksUnassigned' => Task::query()
                    ->where('user_id', null)
                    ->count(),
            ];
        } catch (Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return response()->json([
            'results' => [
                'tasksPerUser' => [
                    'labels' => $tasksPerUser->map(function (User $item) {
                        return $item->full_name;
                    }),
                    'datasets' => [
                        [
                        "label" => "Count",
                        "backgroundColor" => ['red', 'green', 'blue'],
                        "data" => $tasksPerUser->map(function (User $item) {
                                return $item->tasks_count;
                            }),
                        ],
                        [
                            "label" => "Complete",
                            "backgroundColor" => ['purple', 'yellow', 'violet'],
                            "data" => $tasksPerUser->map(function (User $item) {
                                return $item->completed_tasks_count;
                            }),
                        ]
                    ]
                ],
                'tasksCount' => $tasksCount,
            ],
        ]);
    }
}
