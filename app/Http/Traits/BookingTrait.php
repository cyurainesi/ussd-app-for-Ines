<?php


namespace App\Http\Traits;


use App\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

trait BookingTrait
{
    public function sendSMS($phone, $sms)
    {



        $headers = [
            "Content-Type" => "application/json",
            "cmd" => "SEND_SMS",
            "domain" => env("SMS_DOMAIN")
        ];
        $response = Http::withHeaders($headers)->post(env("SMS_URL"), [
            "src" => "BOOKING",
            "dest" => $phone,
            "message" => $sms,
            "wait" => 0,
            "contractId" => env("SMS_CONTRACT_ID")
        ]);
        return json_decode($response->body())->code == 100;

    }

    public function storeBooking(Request $request, $isUssd = false)
    {
        $bookings = Booking::where("destination_id", $request["destination_id"])->whereDate("created_at", now()->toDateString())->count();
        if ($bookings >= 24)
            return response()->json(["message" => "All places are taken!"]);

        $transaction_id = substr(now()->year, -2) . "HOR" . random_int(10000, 99999);
        $request["transaction_id"] = $transaction_id;
        Booking::create($request->only("amount", "names", "phone_number", "destination_id", "transaction_id", "departure_time"));
        if ($isUssd)
            $this->momoPay($transaction_id, $request->amount, $request->phone_number);
        else
            return $this->pay($transaction_id, $request->amount, $request->names, $request->phone_number, "Travel payment")["data"]["link"];
//
//        $this->sendSMS("25" . $request->phone_number, "Please use this link to finish the payment: " . $paymentLink);

    }

    public function momoPay($tx_ref, $amount, $phoneNumber)
    {
        $URL = "https://opay-api.oltranz.com/opay/paymentrequest";
        Http::post($URL, [
            "telephoneNumber" => "25" . $phoneNumber,
            "amount" => $amount,
            "organizationId" => "e4137d92-e7f6-456d-93f5-4b958d2397a7",
            "description" => "Payment",
            "callbackUrl" => "https://horizon-booking.herokuapp.com/api/opay/payment-response",
            "transactionId" => $tx_ref
        ]);
    }

    private function pay($tx_ref, $amount, $names, $phoneNumber, $title)
    {
//        $currentURL = Request::url();
        $URL = "https://api.flutterwave.com/v3/payments";
        $SECRET_KEY = "FLWSECK_TEST-25733e34f6bdebf0b6e4a6210e80dcf6-X";
        $response = Http::withToken($SECRET_KEY)->post($URL,
            [
                "tx_ref" => $tx_ref,
                "amount" => $amount,
                "currency" => "RWF",
                "redirect_url" => "https://horizon-booking.herokuapp.com/payment-success",
                "payment_options" => "card",
                "customer" => [
                    "phonenumber" => $phoneNumber,
                    "name" => $names,
                    "email" => "alainmucyo3@gmail.com"
                ],
                "customizations" => [
                    "title" => "Horizon",
                    "description" => "Payment for $title",
                ]
            ]
        );
        return json_decode($response, true);
    }
}
