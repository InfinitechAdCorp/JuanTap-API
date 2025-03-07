<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Hash;

use App\Models\User as Model;
use App\Models\Provider;

class AuthController extends Controller
{
    public $model = "User";

    public function link(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|max:255|exists:users,id',
            'username' => 'nullable|max:255',
            'password' => 'nullable|min:8|max:255',
            'role' => 'nullable|max:255',
            'name' => 'required|max:255',
            'account_id' => 'required|max:255',
            'access_token' => 'required|max:255',
        ]);

        $record = Model::find($validated['id']);
        $validated['user_id'] = $record->id;

        $record->update($validated);
        Provider::updateOrCreate(['user_id' => $validated['user_id']], $validated);

        $response = [
            'message' => "Account Linked",
            'record' => $record,
        ];
        $code = 200;
        return response()->json($response, $code);
    }

    public function signupByCredentials(Request $request)
    {
        $validated = $request->validate([
            'username' => 'nullable|max:255',
            'email' => 'required|max:255|email|unique:users,email',
            'password' => 'required|min:8|max:255',
            'role' => 'nullable|max:255',
        ]);
        $validated['password'] = Hash::make($validated['password']);

        $record = Model::create($validated);

        $response = [
            'message' => "Created $this->model",
            'record' => $record,
        ];
        $code = 201;
        return response()->json($response, $code);
    }

    public function upsert(Request $request)
    {
        $name = strtolower($request->name);

        if ($name == "google") {
            $validated = $request->validate([
                'username' => 'nullable|max:255',
                'email' => 'required|max:255|email',
                'password' => 'nullable|min:8|max:255',
                'role' => 'nullable|max:255',
                'name' => 'required|max:255',
                'account_id' => 'required|max:255',
                'access_token' => 'required|max:255',
            ]);
        } else {
            $validated = $request->validate([
                'username' => 'nullable|max:255',
                'email' => 'required|max:255|email',
                'password' => 'required|min:8|max:255',
                'role' => 'nullable|max:255',
                'name' => 'required|max:255',
            ]);
            $validated['password'] = Hash::make($validated['password']);
        }

        $record = Model::updateOrCreate(
            ['email' => $validated['email']],
            $validated
        );

        if ($name) {
            $validated['user_id'] = $record->id;
            Provider::updateOrCreate(
                ['user_id' => $record->id],
                $validated,
            );
        }

        $action = $record->wasRecentlyCreated ? "Created" : "Updated";
        $response = [
            'message' => "$action $this->model",
            'record' => $record,
        ];
        $code = $action == "Created" ? 201 : 200;
        return response()->json($response, $code);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $record = Model::where('email', $validated['email'])->first();
        $isValid = Hash::check($validated['password'], $record->password);

        if ($record && $isValid) {
            $response = [
                'message' => 'Logged In Successfully',
                'record' => $record,
            ];
            $code = 200;
        } else {
            $response = [
                'message' => 'Invalid Credentials',
            ];
            $code = 401;
        }
        return response()->json($response, $code);
    }

    public function logout(Request $request)
    {
        $record = PersonalAccessToken::findToken($request->bearerToken())->tokenable;
        PersonalAccessToken::where('tokenable_id', $record->id)->delete();

        $response = ['message' => 'Logged Out Successfully'];
        $code = 200;
        return response()->json($response, $code);
    }
}
