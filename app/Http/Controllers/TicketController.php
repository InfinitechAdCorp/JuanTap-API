<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Traits\Uploadable;

use App\Models\Ticket as Model;

class TicketController extends Controller
{
    use Uploadable;

    public $model = "Ticket";

    public $relations = ["user"];

    public $rules = [
        'type' => 'required|max:255',
        'subject' => 'required|max:255',
        'description' => 'required',
        'status' => 'required|max:255',
        'image' => 'required',
    ];

    public $directory = "tickets";

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
        $user_id = $request->header('user-id');
        $validated = $request->validate($this->rules);
        $validated['user_id'] = $user_id;

        $key = 'image';
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
        $user_id = $request->header('user-id');
        $this->rules['id'] = 'required|exists:tickets,id';
        $validated = $request->validate($this->rules);
        $validated['user_id'] = $user_id;

        $record = Model::find($validated['id']);

        $key = 'image';
        if ($request->hasFile($key)) {
            Storage::disk('s3')->delete($this->directory."/$record[$key]");
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
}
