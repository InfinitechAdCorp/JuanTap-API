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
    public $relations = ['provider', 'profile.socials', 'customizations_templates', 'collections_templates', 'payments_templates'];

    public function get(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|max:255|exists:users,id',
        ]);

        $record = Model::with($this->relations)->where('id', $validated['id'])->first();
        $response = ['message' => "Fetched $this->model", 'record' => $record];
        $code = 200;
        return response()->json($response, $code);
    }

    public function getByEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|max:255|exists:users,email',
        ]);

        $record = Model::with($this->relations)->where('email', $validated['email'])->first();
        $response = ['message' => "Fetched $this->model", 'record' => $record];
        $code = 200;
        return response()->json($response, $code);
    }

    public function signup(Request $request)
    {
        $rules = [
            'username' => 'nullable|max:255|unique:users,username',
            'email' => 'required|max:255|email|unique:users,email',
            'role' => 'nullable|max:255',
        ];
        $name = strtolower($request->name);

        if ($name == 'credentials') {
            $rules = [...$rules, 'password' => 'required|min:8|max:255'];
        } else {
            $rules = [
                ...$rules,
                'password' => 'nullable|min:8|max:255',
                'name' => 'required|max:255',
                'account_id' => 'required|max:255',
                'access_token' => 'required|max:255',
            ];
        }

        $validated = $request->validate($rules);
        if ($name == 'credentials') {
            $validated['password'] = Hash::make($validated['password']);
        }

        $record = Model::create($validated);

        if ($name == 'google') {
            Provider::create([...$validated, 'user_id' => $record->id]);
        }

        $response = [
            'message' => "Created $this->model",
            'record' => $record,
        ];
        $code = 201;
        return response()->json($response, $code);
    }

    public function signin(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|max:255|email',
            'password' => 'required|min:8|max:255',
        ]);

        $record = Model::with($this->relations)->where('email', $validated['email'])->first();
        $isValid = $record ? Hash::check($validated['password'], $record->password) : false;

        $response = [
            'message' => $isValid ? 'Logged In Successfully' : 'Invalid Credentials',
            'record' => $isValid ? $record : null,
        ];
        $code = $isValid ? 200 : 401;
        return response()->json($response, $code);
    }
}
