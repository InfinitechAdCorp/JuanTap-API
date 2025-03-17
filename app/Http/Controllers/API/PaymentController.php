<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Payment as Model;

class PaymentController extends Controller
{
    public $model = "Payment";
    public $relations = ['user'];

    public $rules = [
        'user_id' => 'required|exists:users,id',
        'reference_number' => 'required|max:255',
        'checkout_url' => 'required|max:255',
        'amount' => 'required|decimal:0,2',
        'remarks' => 'required|max:255',
        'method' => 'required|max:255',
        'status' => 'required|max:255',
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
        $this->rules['id'] = 'required|exists:payments,id';
        $validated = $request->validate($this->rules);

        $record = Model::find($validated['id']);
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
        $payload = json_decode($request->getContent(), true);
        $data = $payload["data"]["attributes"]["data"]["attributes"];
        
        $record = Model::where('reference_number', $data['external_reference_number']);
        $record->update(["status" => $data["status"]]);

        $code = 200;
        $response = [
            'message' => "Updated Status of $this->model",
            'record' => $record,
        ];
        return response()->json($response, $code);
    }
}
