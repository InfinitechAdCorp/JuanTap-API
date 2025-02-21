<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Traits\Uploadable;

use App\Models\Profile as Model;
use App\Models\Provider;

class ProfileController extends Controller
{
    use Uploadable;

    public $model = "Profile";

    public $rules = [
        'user_id' => 'required|exists:users,id',
        'template_id' => 'required|exists:templates,id',
        'name' => 'required|max:255',
        'location' => 'required|max:255',
        'bio' => 'required',
        'avatar' => 'required|file',
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
        $rules = [
            'user_id' => 'required|exists:users,id',
            'template_id' => 'required|exists:templates,id',
            'name' => 'required|max:255',
            'location' => 'required|max:255',
            'bio' => 'required',
            'avatar' => 'required|file',
        ];
        $validated = $request->validate($rules);

        $key = 'avatar';
        if ($request->hasFile($key)) {
            $validated[$key] = $this->upload($request->file($key), "avatars");
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
        $rules = [
            'id' => 'required|exists:profiles,id',
            'user_id' => 'nullable|exists:users,id',
            'template_id' => 'nullable|exists:templates,id',
            'name' => 'nullable|max:255',
            'location' => 'nullable|max:255',
            'bio' => 'nullable',
            'avatar' => 'nullable|file',
        ];
        $validated = $request->validate($rules);

        $record = Model::find($validated['id']);

        $key = 'avatar';
        if ($request->hasFile($key)) {
            Storage::disk('s3')->delete("avatars/$record[$key]");
            $validated[$key] = $this->upload($request->file($key), "avatars");
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
            Storage::disk('s3')->delete("avatars/$record->avatar");

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

    public function upsertGeneralProfile(Request $request) {
        $validated = $request->validate([
            'id' => 'required|max:255|exists:users,id',
            'username' => 'nullable|max:255',
            'email' => 'nullable|max:255|email',
        ]);

        $record = Model::find($validated['id']);
    }
}
