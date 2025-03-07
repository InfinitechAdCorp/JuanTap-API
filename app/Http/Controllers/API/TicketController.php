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

    public $rules = [
        'subject' => 'required|max:255',
        'description' => 'required',
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

    public function setStatus(Request $request) {
        $rules = [
            'id' => 'required|exists:tickets,id',
            'status' => 'required|max:255',
        ];
        $validated = $request->validate($rules);

        $record = Model::find($validated['id']);
        $record->update($validated);
        $code = 200;
        $response = ['message' => "Updated $this->model", 'record' => $record];
        return response()->json($response, $code);
    }

    public function track($number)
    {
        $record = Model::with($this->relations)->where('number', $number)->first();
        if ($record) {
            $code = 200;
            $response = ['message' => "Fetched $this->model", 'record' => $record];
        } else {
            $code = 404;
            $response = ['message' => "$this->model Not Found"];
        }
        return response()->json($response, $code);
    }
}
