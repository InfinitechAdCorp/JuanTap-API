<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Uploadable;

use App\Models\Template as Model;

class TemplateController extends Controller
{
    use Uploadable;

    public $model = "Template";
    public $relations = ["collections_users", "favorites_users"];
    public $directory = "templates";

    public $rules = [
        'name' => 'required|max:255',
        'price' => 'required|decimal:0,2',
        'description' => 'required',
        'content' => 'required',
        'thumbnail' => 'required',
    ];

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
            $response = ['message' => "Fetched $this->model", 'record' => $record];
            $code = 200;
        } else {
            $response = ['message' => "$this->model Not Found"];
            $code = 404;
        }
        return response()->json($response, $code);
    }

    public function create(Request $request)
    {
        $validated = $request->validate($this->rules);

        $key = 'thumbnail';
        if ($request->hasFile($key)) {
            $validated[$key] = $this->upload($this->directory, $request->file($key));
        }

        $record = Model::create($validated);

        $response = [
            'message' => "Created $this->model",
            'record' => $record,
        ];
        $code = 201;
        return response()->json($response, $code);
    }

    public function update(Request $request)
    {
        $this->rules['id'] = 'required|exists:templates,id';
        $this->rules['thumbnail'] = 'nullable';
        $validated = $request->validate($this->rules);

        $record = Model::find($validated['id']);

        $key = 'thumbnail';
        if ($request->hasFile($key)) {
            $validated[$key] = $this->upload($this->directory, $request->file($key));
        }

        $record->update($validated);

        $response = ['message' => "Updated $this->model", 'record' => $record];
        $code = 200;
        return response()->json($response, $code);
    }

    public function delete($id)
    {
        $record = Model::find($id);
        if ($record) {
            $record->delete();
            $response = ['message' => "Deleted $this->model"];
            $code = 200;
        } else {
            $response = ['message' => "$this->model Not Found"];
            $code = 404;
        }
        return response()->json($response, $code);
    }
}
