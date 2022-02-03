<?php

namespace App\Http\Ussd\States;

use App\Destination;
use Sparors\Ussd\State;

class Welcome extends State
{
    protected function beforeRendering(): void
    {
        $destinations = Destination::pluck("name")->toArray();
        $this->menu->text('Murakaza neza kuri Horizon.')
            ->lineBreak(2)
            ->line('Muhitemo urugendo')
            ->listing($destinations);
    }


    protected function afterRendering(string $argument): void
    {
        $explodedString = explode("*", $argument);
        $text = $explodedString[count($explodedString) - 1];

        $this->record->set("destination", $text);
        $this->decision
            ->any(ClientNames::class);
    }
}
