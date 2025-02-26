<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

use App\Models\User as Model;
use App\Models\Provider;

class UserController extends Controller
{
    public $model = "User";

    public $relations = ['provider'];

    public function getAll()
    {
        $records = Model::with($this->relations)->get();
        $code = 200;
        $response = ['message' => "Fetched $this->model" . "s", 'records' => $records];
        return response()->json($response, $code);
    }

    public function get($id)
    {
        $record = Model::with($this->relations)->where('id', $id)->first();
        if ($record) {
            $code = 200;
            $response = ['message' => "Fetched $this->model", 'record' => $record];
        } else {
            $code = 404;
            $response = ['message' => "$this->model Not Found"];
        }
        return response()->json($response, $code);
    }

    public function getByEmail(Request $request)
    {
        $account_id = $request->account_id;
        $record = Model::with($this->relations)->whereHas('provider', function ($result) use ($account_id) {
            $result->where('account_id', $account_id);
        })->first();

        if (!$record) {
            $record = Model::with($this->relations)->where('email', $request->email)->first();
        }

        if ($record) {
            $code = 200;
            $response = ['message' => "Fetched $this->model", 'record' => $record];
        } else {
            $code = 404;
            $response = ['message' => "$this->model Not Found"];
        }
        return response()->json($response, $code);
    }

    public function linkOAuth(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|max:255|exists:users,id',
            'username' => 'nullable|max:255|unique:users,username',
            'password' => 'nullable|min:8|max:255',
            'type' => 'nullable|max:255',
            'name' => 'required|max:255',
            'account_id' => 'required|max:255',
            'access_token' => 'required|max:255',
        ]);

        $record = Model::find($validated['id']);
        $validated['user_id'] = $record->id;
        $record->update($validated);
        Provider::updateOrCreate(['user_id' => $validated['user_id']], $validated);

        $code = 200;
        $response = [
            'message' => "Account Linked",
            'record' => $record,
        ];
        return response()->json($response, $code);
    }

    public function signupByCredentials(Request $request)
    {
        $validated = $request->validate([
            'username' => 'nullable|max:255|unique:users,username',
            'email' => 'required|max:255|email|unique:users,email',
            'password' => 'required|min:8|max:255',
            'type' => 'nullable|max:255',
        ]);
        $validated['password'] = Hash::make($validated['password']);

        $record = Model::create(
            $validated
        );
        $code = 201;
        $response = [
            'message' => "Created $this->model",
            'record' => $record,
        ];
        return response()->json($response, $code);
    }

    public function upsert(Request $request)
    {
        $name = strtolower($request->name);

        if ($name == "google") {
            $validated = $request->validate([
                'username' => 'nullable|max:255|unique:users,username',
                'email' => 'required|max:255|email',
                'password' => 'nullable|min:8|max:255',
                'type' => 'nullable|max:255',
                'name' => 'required|max:255',
                'account_id' => 'required|max:255',
                'access_token' => 'required|max:255',
            ]);
        } else {
            $validated = $request->validate([
                'username' => 'nullable|max:255|unique:users,username',
                'email' => 'required|max:255|email',
                'password' => 'required|min:8|max:255',
                'type' => 'nullable|max:255',
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

        $code = $record->wasRecentlyCreated ? 201 : 200;
        $action = $code == 201 ? "Created" : "Updated";
        $response = [
            'message' => "$action $this->model",
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
        $isValid = Hash::check($validated['password'], $record->password);

        if ($record && $isValid) {
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

    public function updateGeneralSettings(Request $request)
    {
        $user_id = $request->header('user-id');
        $validated = $request->validate([
            'username' => 'nullable|max:255',
            'email' => 'nullable|max:255|email',
        ]);

        $record = Model::find($user_id);
        $record->update($validated);
        $code = 200;
        $response = ['message' => "Updated General Settings", 'record' => $record];
        return response()->json($response, $code);
    }

    public function updatePassword(Request $request)
    {
        $user_id = $request->header('user-id');
        $validated = $request->validate([
            'old' => 'nullable|min:8|max:255',
            'new' => 'nullable|min:8|max:255',
        ]);

        $record = Model::find($user_id);
        $isValid = Hash::check($validated['old'], $record->password);
        if ($isValid) {
            $record->update([
                'password' => Hash::make($validated['new']),
            ]);
            $code = 200;
            $response = ['message' => "Updated Password", 'record' => $record];
        } else {
            $code = 422;
            $response = ['message' => "Invalid Password"];
        }

        return response()->json($response, $code);
    }
}
