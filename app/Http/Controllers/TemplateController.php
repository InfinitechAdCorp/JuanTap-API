<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Traits\Uploadable;

use App\Models\Template as Model;
use App\Models\User;
use App\Models\TemplateUser;

class TemplateController extends Controller
{
    use Uploadable;

    public $model = "Template";

    public $rules = [
        'name' => 'required|max:255',
        'price' => 'required|decimal:0,2',
        'description' => 'required',
        'file' => 'required',
        'thumbnail' => 'required',
    ];

    public $directory = "templates";

    public function getAll()
    {
        $records = Model::all();
        $response = ['message' => "Fetched $this->model" . "s", 'records' => $records];
        $code = 200;
        return response()->json($response, $code);
    }

    public function get($id)
    {
        $record = Model::where('id', $id)->first();
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

        $key = 'thumbnail';
        if ($request->hasFile($key)) {
            $validated[$key] = $this->upload($request->file($key), $this->directory);
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
        $this->rules['id'] = 'required|exists:templates,id';
        $this->rules['thumbnail'] = 'nullable';
        $validated = $request->validate($this->rules);

        $record = Model::find($validated['id']);

        $key = 'thumbnail';
        if ($request->hasFile($key)) {
            Storage::disk('s3')->delete($this->directory . "/$record[$key]");
            $validated[$key] = $this->upload($request->file($key), $this->directory);
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

    public function publishTemplate(Request $request, $id)
    {
        $data['user_id'] = $request->header('user-id');
        $data['template_id'] = $id;
        $data['published'] = 1;

        $where = [
            ['user_id', $data['user_id']],
            ['template_id', $data['template_id']],
        ];
        $record = TemplateUser::where($where)->first();

        if ($record && $record->published == 1) {
            $code = 422;
            $response = [
                'message' => "$this->model Is Already Published",
            ];
        } else {
            TemplateUser::where('user_id', $data['user_id'])->update(['published' => 0]);

            TemplateUser::updateOrcreate(['template_id' => $data['templated_id']], $data);
            $record = User::with('templates')->where('id', $data['user_id'])->first();
            $code = 200;
            $response = [
                'message' => "Published $this->model",
                'record' => $record,
            ];
        }
        return response()->json($response, $code);
    }
}
