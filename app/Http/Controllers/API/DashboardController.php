<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Template;
use App\Models\Ticket;

class DashboardController extends Controller
{
    public function getCounts()
    {
        $counts = [
            'users' => User::get()->count(),
            'templates' => Template::get()->count(),
            'tickets' => Ticket::get()->count(),
        ];

        $templates = Template::with('collections_users')->get();
        foreach ($templates as $template) {
            $template['users_count'] = count($template['collections_users']);
        }

        $response = ['message' => "Fetched Data", 'counts' => $counts, 'templates' => $templates];
        $code = 200;
        return response()->json($response, $code);
    }
}
