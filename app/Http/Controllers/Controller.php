<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    protected function successResponse(mixed $data = null, ?string $message = null, int $status = Response::HTTP_OK)
    {
        return response()->json([
            'success' => true, 
            'message' => $message, 
            'data' => $data, 
            'timestamp' => now()->format('Y-m-d H:i:s')], 
            $status
        );
    }
    protected function errorResponse(string $message, ?array $errors = null, int $status = Response::HTTP_UNPROCESSABLE_ENTITY)
    {
        return response()->json([
            'success' => false, 
            'message' => $message, 
            'errors' => $errors, 
            'timestamp' => now()->format('Y-m-d H:i:s')], 
            $status);
    }
}
