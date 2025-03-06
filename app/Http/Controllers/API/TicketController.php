<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Uploadable;

use App\Models\Ticket as Model;

class TicketController extends Controller
{
    use Uploadable;

    public $model = "Ticket";
    public $relations = ["user"];
    public $directory = "tickets";

    public $rules = [
        'user_id' => 'required|exists:users,id',
        'subject' => 'required|max:255',
        'description' => 'required',
        'type' => 'required|max:255',
        'status' => 'required|max:255',
        'image' => 'required',
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

        $key = 'image';
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
        $this->rules['id'] = 'required|exists:tickets,id';
        $this->rules['image'] = 'nullable';
        $validated = $request->validate($this->rules);

        $record = Model::find($validated['id']);

        $key = 'image';
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

    public function setStatus(Request $request)
    {
        $rules = [
            'id' => 'required|exists:tickets,id',
            'status' => 'required|max:255',
        ];
        $validated = $request->validate($rules);

        $record = Model::find($validated['id']);
        $record->update($validated);

        $response = ['message' => "Updated Status of $this->model", 'record' => $record];
        $code = 200;
        return response()->json($response, $code);
    }
}
