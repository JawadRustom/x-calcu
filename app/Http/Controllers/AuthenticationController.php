<?php

namespace App\Http\Controllers;

use App\Enums\RoleTypeEnum;
use App\Http\Requests\AuthenticationRequests\LoginRequest;
use App\Http\Requests\AuthenticationRequests\RegisterRequest;
use App\Http\Requests\AuthenticationRequests\UpdateProfileRequest;
use App\Http\Resources\AuthenticationResources\UserResource;
use App\Mail\ForgetPasswordEmail;
use App\Models\PasswordReset;
use App\Models\User;
use App\Traits\ResultTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationController extends Controller
{
    use ResultTrait;

    /**
     * Handle user login
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            if (!$user) {
                return response()->json([
                    'message' => 'Invalid email address.',
                    'errors' => [
                        'email' => ['We couldn’t find an account with that email address.'],
                    ]
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            return response()->json([
                'message' => 'Invalid password.',
                'errors' => [
                    'password' => ['The password you entered is not correct. Please try again.']
                ]
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        return $this->successResponse([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], "Login successfully", 200);

    }

    /**
     * Handle user logout
     */
    public function logout(): Response
    {
        Auth::user()->tokens()->delete();

        return response()->noContent();
    }

    /**
     * Get authenticated user data
     */
    public function user(): JsonResponse
    {
        return $this->successResponse(new \App\Http\Resources\UserResource(Auth::user()), "User data", 200);
    }

}
