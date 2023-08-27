<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/signup",
     *     tags={"Auth"},
     *     summary="Creates new user",
     *     operationId="signup",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Victoria"),
     *             @OA\Property(property="email", type="string", example="victoria.v@gmail.com"),
     *             @OA\Property(property="password", type="string", example="Vica777_")
     *         )
     *      ),
     *     @OA\Response(
     *         response=201,
     *         description="user created",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User created successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Operation failed.")
     *         )
     *     )
     * )
     */
    public function signup(SignupRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($user === null) {
            return response()->json([
                'message' => 'User creation is failed.'
            ], 400);
        }

        return response()->json([
            'message' => 'User created successfully.'
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Auth"},
     *     summary="Creates authentication token",
     *     operationId="login",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="victoria.v@gmail.com"),
     *             @OA\Property(property="password", type="string", example="Vica777_")
     *         )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="user loggedin",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User logged in successfully."),
     *             @OA\Property(property="token", type="string", example="1|laravel_sanctum_j2VoZ1wDmAwK7Oif8KFs8a8XbX2tVIRBT2hprRPK358b512b")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Operation failed.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Logging is failed.")
     *         )
     *     ),
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'message' => 'Logging is failed.'
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

        return response()->json([
            'message' => 'User logged in successfully.',
            'token' => $user->createToken('API_TOKEN')->plainTextToken,
        ], 200);
    }
}
