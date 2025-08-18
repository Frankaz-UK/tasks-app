<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

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
