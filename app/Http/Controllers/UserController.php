<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Change;

class UserController extends Controller
{
    public function getChangesByMonth() {
        $records = Change::selectRaw('id, name, type, description, date, YEAR(date) year, MONTH(date) month')
                   ->orderBy('year', 'desc')
                   ->orderBy('month', 'desc')
                   ->get();

        $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        foreach ($records as $record) {
            $record['month_year'] = $months[$record->month - 1] . " $record->year";
        }

        $records = $records->groupBy('month_year');

        $response = ['message' => "Fetched Changes", 'records' => $records];
        $code = 200;
        return response()->json($response, $code);
    }
}

