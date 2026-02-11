<?php

namespace App\Http\Controllers\Api\Auth;

use App\Enums\Titles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserListRequest;
use App\Http\Requests\Auth\UserRequest;
use App\Models\Task;
use App\Models\User;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param UserListRequest $request
     * @return JsonResponse
     */
    public function index(UserListRequest $request): JsonResponse
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
                    !empty($request->filled('role')),
                    function (Builder $query) use ($request): void {
                        $query
                            ->whereHas('roles', function ($query) use ($request): void {
                                $query->where('name', '=', $request->input('role'));
                            });
                    },
                )
                ->where('id', '!=', auth()->user()->id)
                ->with('roles.permissions')
                ->with('permissions')
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
     * @param User $user
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function store(User $user, UserRequest $request)
    {
        try {
            $user->title = $request->input('title');
            $user->forename = $request->input('forename');
            $user->surname = $request->input('surname');
            $user->email = $request->input('email');
            $user->position = $request->input('position');
            $user->telephone  = $request->input('telephone');
            $user->gender = $request->input('gender');
            $user->syncRoles($request->input('role'));
            $user->save();
        } catch (Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return response()->json([
            'message' => 'User successfully added',
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param User $user
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function update(User $user, UserRequest $request)
    {
        try {
            $user->title = $request->input('title');
            $user->forename = $request->input('forename');
            $user->surname = $request->input('surname');
            $user->email = $request->input('email');
            $user->position = $request->input('position');
            $user->telephone  = $request->input('telephone');
            $user->gender = $request->input('gender');
            $user->syncRoles($request->input('role'));
            $user->syncPermissions($request->input('permissions'));
            $user->save();
        } catch (Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return response()->json([
            'message' => 'User successfully updated',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return JsonResponse
     */
    public function destroy(User $user)
    {
        try {
            $user->tasks()->each(function (Task $task) {
                $task->unSetUser();
            });
            $user->delete();
        } catch (Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return response()->json([
            'message' => 'User successfully deleted',
        ]);
    }

    /**
     * @return JsonResponse
     */
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

    /**
     * @return JsonResponse
     */
    public function getTitlesList(): JsonResponse
    {
        try {
            $titles = Titles::cases();
        } catch (Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return response()->json([
            'results' => $titles,
        ]);
    }
}
