<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Traits\Uploadable;

use App\Models\Ticket as Model;

class TicketController extends Controller
{
    use Uploadable;

    public $model = "Ticket";

    public $rules = [
        'name' => 'required|max:255',
        'file' => 'required',
        'thumbnail' => 'required',
    ];

    public $directory = "Ticket";

    
}
