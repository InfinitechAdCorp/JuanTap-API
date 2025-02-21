<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
        $account_id = $request->provider_account_id;
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
            'password' => 'nullable|min:8|max:255',
            'type' => 'nullable|max:255',
            'provider' => 'required|max:255',
            'provider_account_id' => 'required|max:255',
            'access_token' => 'required|max:255',
        ]);

        $record = Model::find($validated['id']);

        if (!$record->provider) {
            Provider::create([
                'user_id' => $record->id,
                'name' => $validated['provider'],
                'account_id' => $validated['provider_account_id'],
                'access_token' => $validated['access_token'],
            ]);

            $code = 200;
            $response = [
                'message' => "Account Linked",
                'record' => $record,
            ];
        } else {
            $code = 200;
            $response = [
                'message' => "Account Is Already Linked",
                'record' => $record,
            ];
        }

        return response()->json($response, $code);
    }

    public function upsert(Request $request)
    {
        $provider = strtolower($request->provider);

        if ($provider == "google") {
            $validated = $request->validate([
                'email' => 'required|max:255|email',
                'password' => 'nullable|min:8|max:255',
                'type' => 'nullable|max:255',
                'provider' => 'required|max:255',
                'provider_account_id' => 'required|max:255',
                'access_token' => 'required|max:255',
            ]);
        } else {
            $validated = $request->validate([
                'email' => 'required|max:255|email',
                'password' => 'required|min:8|max:255',
                'type' => 'nullable|max:255'
            ]);

            $validated['password'] = Hash::make($validated['password']);
        }

        $record = Model::updateOrCreate(
            ['email' => $validated['email']],
            [
                'email' => $validated['email'],
                'password' => $validated['password'] ?? null,
                'type' => $validated['type'] ?? "User",
            ]
        );

        if ($provider) {
            Provider::updateOrCreate(
                ['user_id' => $record->id],
                [
                    'user_id' => $record->id,
                    'name' => $validated['provider'],
                    'account_id' => $validated['provider_account_id'],
                    'access_token' => $validated['access_token'],
                ]
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

    public function logout(Request $request)
    {
        $record = PersonalAccessToken::findToken($request->bearerToken())->tokenable;
        PersonalAccessToken::where('tokenable_id', $record->id)->delete();

        $code = 200;
        $response = ['message' => 'Logged Out Successfully'];
        return response()->json($response, $code);
    }
}
