<?php

namespace App\Http\Controllers\Admin;

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

    public function upsert(Request $request)
    {
        $user_id = $request->header('user-id');
        $rules = [
            'template_id' => 'nullable|exists:templates,id',
            'name' => 'nullable|max:255',
            'location' => 'nullable|max:255',
            'bio' => 'nullable',
            'avatar' => 'nullable|file',
            'socials' => 'nullable|array',
        ];
        $validated = $request->validate($rules);

        $record = Model::where('user_id', $validated['user_id'])->first();

        if ($record) {
            $key = 'avatar';
            if ($request->hasFile($key)) {
                Storage::disk('s3')->delete("avatars/$record[$key]");
                $validated[$key] = $this->upload($request->file($key), "avatars");
            }

            $record->socials()->delete();
        }

        $record->updateOrCreate(
            ['user_id' => $user_id],
            $validated
        );

        $key = 'socials';
        if ($request[$key]) {
            foreach ($request[$key] as $social) {
                Social::create([
                    'profile_id' => $record->id,
                    'name' => $social->name,
                    'link' => $social->link,
                ]);
            }
        }

        $code = $record->wasRecentlyCreated ? 201 : 200;
        $action = $code == 201 ? "Created" : "Updated";
        $response = [
            'message' => "$action $this->model",
            'record' => $record,
        ];
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
