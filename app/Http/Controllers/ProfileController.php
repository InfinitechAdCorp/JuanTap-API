<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Traits\Uploadable;

use App\Models\Profile as Model;
use App\Models\Social;

class ProfileController extends Controller
{
    use Uploadable;

    public $model = "Profile";

    public $relations = ["user", "socials"];

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

        $rules = [
            'name' => 'nullable|max:255',
            'location' => 'nullable|max:255',
            'bio' => 'nullable',
            'avatar' => 'nullable',
            'socials' => 'nullable',
        ];
        $validated = $request->validate($rules);

        $validated['user_id'] = $user_id;

        $key = 'avatar';
        $validated[$key] = $this->upload($request->file($key), "avatars");

        $record = Model::create($validated);

        $key = 'socials';
        if ($validated[$key]) {
            foreach ($validated[$key] as $social) {
                Social::create([
                    'profile_id' => $record->id,
                    'platform' => $social['platform'],
                    'url' => $social['url'],
                ]);
            }
        }

        $record = Model::with($this->relations)->where('id', $record->id)->first();
        return $record;
    }

    public function update(Request $request)
    {
        $user_id = $request->header('user-id');

        $rules = [
            'name' => 'nullable|max:255',
            'location' => 'nullable|max:255',
            'bio' => 'nullable',
            'avatar' => 'nullable',
            'socials' => 'nullable',
        ];
        $validated = $request->validate($rules);

        $record = Model::where('user_id', $user_id)->first();

        $validated['user_id'] = $user_id;

        $key = 'avatar';
        if ($request->hasFile($key)) {
            Storage::disk('s3')->delete("avatars/$record[$key]");
            $validated[$key] = $this->upload($request->file($key), "avatars");
        }

        $record->update($validated);

        $record->socials()->delete();

        $key = 'socials';
        if ($validated[$key]) {
            foreach ($validated[$key] as $social) {
                Social::create([
                    'profile_id' => $record->id,
                    'platform' => $social['platform'],
                    'url' => $social['url'],
                ]);
            }
        }

        $record = Model::with($this->relations)->where('id', $record->id)->first();
        return $record;
    }

    public function upsert(Request $request)
    {
        $user_id = $request->header('user-id');
        $record = Model::where('user_id', $user_id)->first();
        if ($record) {
            $record = $this->update($request);
            $code = 200;
            $response = ['message' => "Updated $this->model", 'record' => $record];
        } else {
            $record = $this->create($request);
            $code = 201;
            $response = ['message' => "Created $this->model", 'record' => $record];
        }

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
}
