<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\User\Types\EmailType;
use App\User\Types\IntType;
use App\User\Types\StringType;
use Illuminate\Http\Request;
use InvalidArgumentException;

class UserController extends Controller
{
    public function index()
    {
        return view('user');
    }

    public function sanitize(Request $request)
    {
        $data = [
            'name' => $request->input('name'),
            'age' => $request->input('age'),
            'email' => $request->input('email'),
            'description' => $request->input('description'),
        ];

        try {
            $result = [
                'name' => (new StringType())->handle(
                    $data['name'],
                    []
                ),

                'age' => (new IntType())->handle(
                    $data['age'],
                    []
                ),

                'email' => (new EmailType())->handle(
                    $data['email'],
                    []
                ),

                'description' => (new StringType())->handle(
                    $data['description'],
                    []
                ),
            ];

            return response()->json([
                'success' => true,
                'before' => $data,
                'after' => $result,
            ]);

        } catch (InvalidArgumentException $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'before' => $data,
            ], 422);
        }
    }
}