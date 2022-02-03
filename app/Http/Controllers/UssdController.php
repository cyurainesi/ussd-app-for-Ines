<?php

namespace App\Http\Controllers;

use App\Http\Ussd\States\Welcome;
use Illuminate\Http\Request;
use Sparors\Ussd\Facades\Ussd;

class UssdController extends Controller
{
    public function ussdRequest(Request $request)
    {
        $request["sessionId"] = "29454589";
        if (!$request->text)
            $request["text"] = "";
        $ussd = Ussd::machine()
            ->setFromRequest([
                'network',
                'phone_number' => "phone_number",
                'sessionId' => "sessionId",
                'input' => "text"
            ])
            ->setInitialState(Welcome::class)
            ->setResponse(function (string $message, string $action) {
                return "CON $message";
            });

        return $ussd->run();
    }
}
