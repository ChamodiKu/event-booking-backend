<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Exception;

/**
 * Class AuthController
 *
 * Handles user authentication using AuthService.
 */
class AuthController extends Controller
{
    public function __construct(private AuthService $service)
    {
    }

    /**
     * Register a new user.
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        try {
            $user = $this->service->register($request->validated());
            return $this->successResponse($user, 'User registered successfully', 201);
        } catch (Exception $e) {
            Log::error('AuthController@register | ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse('Registration failed', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Login user and return API token.
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        try {
            $data = $this->service->login($request->validated());
            return $this->successResponse($data, 'Login successful');
        } catch (Exception $e) {
            Log::error('AuthController@login | ' . $e->getMessage());
            return $this->errorResponse('Invalid credentials', ['error' => $e->getMessage()], 401);
        }
    }

    /**
     * Logout user and revoke token.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            $this->service->logout($request->user());
            return $this->successResponse(null, 'Logged out successfully');
        } catch (Exception $e) {
            Log::error('AuthController@logout | ' . $e->getMessage());
            return $this->errorResponse('Logout failed');
        }
    }

    /**
     * Return current authenticated user profile.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        try {
            return $this->successResponse($request->user(), 'User profile retrieved');
        } catch (Exception $e) {
            Log::error('AuthController@me | ' . $e->getMessage());
            return $this->errorResponse('Failed to retrieve user data');
        }
    }
}
