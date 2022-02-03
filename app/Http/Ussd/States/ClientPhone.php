<?php

namespace App\Http\Ussd\States;

use App\Destination;
use App\Http\Traits\BookingTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Sparors\Ussd\State;

class ClientPhone extends State
{
    use BookingTrait;

    protected function beforeRendering(): void
    {
        $this->menu->text("Shyiramo numore ya telephone");
    }

    protected function afterRendering(string $argument): void
    {
        $explodedString = explode("*", $argument);
        $text = $explodedString[count($explodedString) - 1];
        $request = new Request();
//        $this->record->set("client_phone", $argument);
        $destinationIndex = $this->record->get("destination");
        $existingDestinations = Destination::get()->toArray();
        $destination = $existingDestinations[$destinationIndex - 1];
        $request["amount"] = $destination["amount"];
        $request["names"] = $this->record->get("client_names");
        $request["phone_number"] = $text;
        $request["destination_id"] = $destination["id"];
//        dd($this->record->get("departure_time"));
        $dpTime = $this->record->get("departure_time");
        $request["departure_time"] = $dpTime;
        $this->storeBooking($request,true);
        $this->decision
            ->any(EndUssd::class);
    }
}
