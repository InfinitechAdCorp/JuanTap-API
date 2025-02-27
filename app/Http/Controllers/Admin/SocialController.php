<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Social as Model;
use App\Models\Profile;

class SocialController extends Controller
{
    public $model = "Social";

    public $relations = ["profile"];

    public function getAll()
    {
        $records = Model::with($this->relations)->get();
        $response = ['message' => "Fetched $this->model" . "s", 'records' => $records];
        $code = 200;
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

    public function create(Request $request)
    {
        $rules = [
            'profile_id' => 'required|exists:profiles,id',
            'platform' => 'required|max:255',
            'url' => 'required|max:255',
        ];
        $validated = $request->validate($rules);

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
            'id' => 'required|exists:socials,id',
            'profile_id' => 'nullable|exists:profiles,id',
            'platform' => 'nullable|max:255',
            'url' => 'nullable|max:255',
        ];
        $validated = $request->validate($rules);

        $record = Model::find($validated['id']);
        $record->update($validated);
        $code = 200;
        $response = ['message' => "Updated $this->model", 'record' => $record];
        return response()->json($response, $code);
    }

    public function delete($id)
    {
        $record = Model::find($id);
        if ($record) {
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