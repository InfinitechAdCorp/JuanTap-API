<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Traits\Uploadable;

use App\Models\Profile as Model;

class ProfileController extends Controller
{
    use Uploadable;

    public $model = "Profile";

    public $rules = [
        'user_id' => 'required|exists:users,id',
        'position' => 'required|max:255',
        'phone' => 'required|max:255',
        'facebook' => 'required|max:255',
        'instagram' => 'required|max:255',
        'telegram' => 'required|max:255',
        'viber' => 'required|max:255',
        'whatsapp' => 'required|max:255',
        'about' => 'required',
        'company' => 'required',
        'image' => 'required',
    ];

    public function getAll()
    {
        $records = Model::with('user')->get();
        $response = ['message' => "Fetched $this->model" . "s", 'records' => $records];
        $code = 200;
        return response()->json($response, $code);
    }

    public function get($id)
    {
        $record = Model::with('user')->where('id', $id)->first();
        if ($record) {
            $code = 200;
            $response = ['message' => "Fetched $this->model", 'record' => $record];
        } else {
            $code = 404;
            $response = ['message' => "$this->model Not Found"];
        }
        return response()->json($response, $code);
    }

    public function create(Request $request)
    {
        $validated = $request->validate($this->rules);

        $key = 'company';
        if ($request->hasFile($key)) {
            $validated[$key] = $this->upload($request->file($key), "profiles/companies");
        }

        $key = 'avatar';
        if ($request->hasFile($key)) {
            $validated[$key] = $this->upload($request->file($key), "profiles/avatars");
        }

        $record = Model::create($validated);
        $code = 201;
        $response = [
            'message' => "Created $this->model",
            'record' => $record,
        ];
        return response()->json($response, $code);
    }

    public function update(Request $request)
    {
        $this->rules['id'] = 'required|exists:profiles,id';
        $this->rules['company'] = 'nullable';
        $this->rules['avatar'] = 'nullable';

        $validated = $request->validate($this->rules);

        $record = Model::find($validated['id']);

        $key = 'company';
        if ($request->hasFile($key)) {
            Storage::disk('s3')->delete("profiles/companies/$record[$key]");
            $validated[$key] = $this->upload($request->file($key), "profiles/companies");
        }

        $key = 'avatar';
        if ($request->hasFile($key)) {
            Storage::disk('s3')->delete("profiles/avatars/$record[$key]");
            $validated[$key] = $this->upload($request->file($key), "profiles/avatars");
        }

        $record->update($validated);
        $code = 200;
        $response = ['message' => "Updated $this->model", 'record' => $record];
        return response()->json($response, $code);
    }

    public function delete($id)
    {
        $record = Model::find($id);
        if ($record) {
            Storage::disk('s3')->delete("profiles/companies/$record->company");
            Storage::disk('s3')->delete("profiles/avatars/$record->avatar");

            $record->delete();
            $code = 200;
            $response = [
                'message' => "Deleted $this->model"
            ];
        } else {
            $code = 404;
            $response = ['message' => "$this->model Not Found"];
        }
        return response()->json($response, $code);
    }
}
