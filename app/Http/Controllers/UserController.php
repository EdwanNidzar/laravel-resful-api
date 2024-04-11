<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (User::where('username', $data['username'])->count() == 1){
            throw new HttpResponseException(response([
                "errors" => [
                    "username" => ["Username already exists"]
                ]
            ], 400));
        }

        $user = new User($data);
        $user->password = Hash::make($data['password']);
        $user->save();

        return (new UserResource($user))->response()->setStatusCode(201);
}

}