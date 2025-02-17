<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

use App\Models\User as Model;

class UserController extends Controller
{
    public $model = "User";

    public function create(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|max:255|unique:users,id',
            'token' => 'required|max:255|unique:users,token',
        ]);

        $record = Model::create($validated);
        $code = 201;
        $response = [
            'message' => "Created $this->model",
            'record' => $record,
        ];
        return response()->json($response, $code);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $record = Model::where('email', $validated['email'])->first();
        $validPassword = Hash::check($validated['password'], $record->password);

        if ($record && $validPassword) {
            $record->tokens()->delete();
            $token = $record->createToken("$record->email-AuthToken")->plainTextToken;
            $code = 200;
            $response = [
                'message' => 'Logged In Successfully',
                'token' => $token,
                'record' => $record,
            ];
        } else {
            $code = 401;
            $response = [
                'message' => 'Invalid Credentials',
            ];
        }
        return response()->json($response, $code);
    }

    public function logout(Request $request)
    {
        $record = PersonalAccessToken::findToken($request->bearerToken())->tokenable;
        PersonalAccessToken::where('tokenable_id', $record->id)->delete();
        $code = 200;
        $response = ['message' => 'Logged Out Successfully'];
        return response()->json($response, $code);
    }

    public function requestReset(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $record = Model::where('email', $validated['email'])->first();
        $code = 200;
        $response = ['message' => 'Request Sent Successfully', 'reset_token' => $record->reset_token];
        return response()->json($response, $code);
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'password' => 'required|min:8|max:255',
            'reset_token' => 'required|exists:users,reset_token',
        ]);

        $record = Model::where('reset_token', $validated['reset_token'])->first();
        $validated['password'] = Hash::make($validated['password']);
        $validated['reset_token'] = Str::random();
        $record->update($validated);
        $code = 200;
        $response = ['message' => 'Reset Password Successfully'];
        return response()->json($response, $code);
    }
}
