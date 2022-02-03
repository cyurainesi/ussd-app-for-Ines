<?php

namespace App\Http\Ussd\States;

use Sparors\Ussd\State;

class EndUssd extends State
{
    protected function beforeRendering(): void
    {
        $this->menu->text("Murakoze gukorana natwe. Mukomeze no kwishyura.");
    }

    protected function afterRendering(string $argument): void
    {
        //
    }
}
