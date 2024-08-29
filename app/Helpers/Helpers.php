<?php

namespace App\Helpers;

use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Helpers
{
    public static function ifValidatorFails($validator)
    {
            return response()->json([
                'status' => false,
                'message' => 'Please fill all the required fields & None of the fields should be empty.',
                'required_fields' => $validator->errors()
            ], 422);
    }
    public static function existResponse($Name)
    {
        return response()->json([
            'status' => false,
            'message' => $Name .' already exist'
        ], 409);
    }
    public static function createResponse($Name, $data)
    {
        return response()->json([
            'status' => true,
            'message' => $Name .' successfully',
            "data" => $data
        ], 201);
    }
    public static function successResponse($Name, $data)
    {
        return response()->json([
            'status' => true,
            'message' => $Name .' successfully',
            "data" => $data
        ], 200);
    }
    public static function failResponse($Name)
    {
        return response()->json([
            'status' => false,
            'message' => $Name .' Failed'
        ], 404);
    }
    public static function catchResponse($e)
    {
        return response()->json([
            'error' => 'Failed to retrieve Details',
            'message' => $e->getMessage()
        ], 500);
    }
}