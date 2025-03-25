<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Uploadable;

use App\Models\User as Model;

class UserController extends Controller
{
    use Uploadable;

    public $model = "User";
    public $directory = "";

    public $rules = [];

    public function getAll()
    {
        $records = Model::get();
        $response = ['message' => "Fetched $this->model" . "s", 'records' => $records];
        $code = 200;
        return response()->json($response, $code);
    }

    public function get($id)
    {
        $record = Model::where('id', $id)->first();
        if ($record) {
            $response = ['message' => "Fetched $this->model", 'record' => $record];
            $code = 200;
        } else {
            $response = ['message' => "$this->model Not Found"];
            $code = 404;
        }
        return response()->json($response, $code);
    }
}
