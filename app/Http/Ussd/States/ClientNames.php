<?php

namespace App\Http\Ussd\States;

use Sparors\Ussd\State;

class ClientNames extends State
{
    protected function beforeRendering(): void
    {
        $this->menu->text('Shyiramo amazina yawe');
    }

    protected function afterRendering(string $argument): void
    {
        $explodedString = explode("*", $argument);
        $text = $explodedString[count($explodedString) - 1];
        $this->record->set("client_names", $text);
        $this->decision
            ->any(DepartureTime::class);
    }
}
