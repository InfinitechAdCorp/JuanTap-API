<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function webhook(Request $request) {
        $webhookSecret = env('WEBHOOK_SECRET');
        $webhookSignature = $request->header('Paymongo_Signature');
        $eventContent = $request->getContent();
        $eventFilter = json_decode($eventContent, true);

        return response($eventContent);
    }
}
