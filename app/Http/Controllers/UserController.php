<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Change;
use App\Models\Ticket;
use App\Models\Template;

class UserController extends Controller
{
    public function getChangesByMonth()
    {
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

    public function trackTicket($number)
    {
        $record = Ticket::with(["user", "statuses"])->where('number', $number)->first();
        if ($record) {
            $response = ['message' => "Fetched Ticket", 'record' => $record];
            $code = 200;
        } else {
            $response = ['message' => "Ticket Not Found"];
            $code = 404;
        }
        return response()->json($response, $code);
    }

    public function viewTemplate($id) {
        $record = Template::find($id);
        $record->update(['views' => $record['views']+1]);

        $response = [
            'message' => "Added View to Template",
            'record' => $record,
        ];
        $code = 200;
        return response()->json($response, $code);
    }
}
