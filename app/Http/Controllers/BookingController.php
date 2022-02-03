<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Destination;
use App\Http\Traits\BookingTrait;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    use BookingTrait;

    public function reports()
    {
        $destinations = Destination::count();
        $totalBookings = Booking::count();
        $todayBookings = Booking::whereDate("created_at", now())->count();
        $scanned = Booking::where("scanned", false)->count();

        return response([
            "today" => $todayBookings,
            "total" => $totalBookings,
            "scanned" => $scanned,
            "destinations" => $destinations
        ]);
    }


    public function index(Request $request)
    {
        $bookings = Booking::with("destination")->latest();
        if ($destination = $request->destination_id) {
            $bookings = $bookings->where("destination_id", $destination);
        }
        return $bookings->get();
    }


    public function store(Request $request)
    {
        $request->validate([
            "amount" => "required",
            "names" => "required",
            "phone_number" => "required|regex:/(07)[0-9]{8}/|max:10",
            "destination_id" => "required",
            "departure_time" => "required",
        ]);
        return $this->storeBooking($request);
    }

    public function paymentResponse(Request $request)
    {
        $booking = Booking::where("transaction_id", $request["txRef"])->first();
        $booking->update(["payed" => true, "payment_mode" => $request["event.type"]]);
        $this->sendSMS("25" . $booking->phone_number,
            "$booking->names, we have received your payment. You booked " . $booking->destination->name . " at $booking->departure_time. Use this code " . $booking->transaction_id);
        return response(["message" => "Ok"]);
    }

    public function opayPaymentResponse(Request $request)
    {
        if ($request["status"] != "SUCCESS") return response(["message" => "transaction failed"]);

        $booking = Booking::where("transaction_id", $request["transactionId"])->first();
        $booking->update(["payed" => true, "payment_mode" => "momo"]);
        $this->sendSMS("25" . $booking->phone_number,
            "$booking->names, we have received your payment. You booked " . $booking->destination->name . " at $booking->departure_time. Use this code " . $booking->transaction_id);
        return response(["message" => "Ok"]);
    }

    public function validateBooking($transactionId)
    {
        $booking = Booking::where("transaction_id", $transactionId)->where("payed", true)->first();
        if (!$booking)
            return response(["message" => "Booking not found"], 404);

        if ($booking->scanned)
            return response(["message" => "Booking already validated"], 402);

        $booking->update(["scanned" => true]);
        return response(["message" => "Scanned successfully"]);

    }
}
