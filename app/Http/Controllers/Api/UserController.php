<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Illuminate\Database\Eloquent\Builder;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $per_page = $request->query('per_page', 15);

            $users = User::query()
                ->when(
                    !empty($request->filled('term')),
                    function (Builder $query) use ($request): void {
                        $query
                            ->where('forename', 'like', '%' . $request->input('term') . '%')
                            ->orWhere('surname', 'like', '%' . $request->input('term') . '%');
                    },
                )
                ->when(
                    !empty($request->filled('user')),
                    function (Builder $query) use ($request): void {
                        $query
                            ->where('id', '=', $request->input('user'));
                    },
                )
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
            'results' => $users,
            'pagination' => [
                'more' => $users->hasMorePages(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        return response()->json([
            'results' => [],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return JsonResponse
     */
    public function update(Request $request, string $id)
    {
        return response()->json([
            'results' => [],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return JsonResponse
     */
    public function destroy(string $id)
    {
        return response()->json([
            'results' => [],
        ]);
    }

    public function getUsersList(): JsonResponse
    {
        try {
            $users = User::query()
                ->select([
                    DB::raw("CONCAT(forename,' ',surname) as fullname"),
                    'id'
                ])
                ->without('roles')
                ->orderBy('fullname')
                ->get();
        } catch (Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return response()->json([
            'results' => $users,
        ]);
    }
}
