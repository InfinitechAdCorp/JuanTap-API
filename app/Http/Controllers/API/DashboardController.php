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
        $counts['users'] = User::get()->count();
        $counts['templates'] = Template::get()->count();
        $counts['tickets'] = Ticket::get()->count();

        $response = ['message' => "Fetched Counts", 'counts' => $counts];
        $code = 200;
        return response()->json($response, $code);
    }
}
