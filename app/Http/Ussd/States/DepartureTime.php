<?php

namespace App\Http\Ussd\States;

use Carbon\Carbon;
use Sparors\Ussd\State;

class DepartureTime extends State
{
    protected function beforeRendering(): void
    {
        $this->menu->text("Igihe cyurugendo.")->lineBreak("2")->text("Ex: 31-10-2021 14:30");
    }

    protected function afterRendering(string $argument): void
    {
        $explodedString = explode("*", $argument);
        $text = $explodedString[count($explodedString) - 1];

        $this->record->set("departure_time", Carbon::parse($text)->toDateTimeString());
        $this->decision
            ->any(ClientPhone::class);
    }
}
