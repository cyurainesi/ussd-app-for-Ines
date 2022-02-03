<?php

namespace App\Http\Ussd\States;

use Sparors\Ussd\State;

class Error extends State
{
    protected function beforeRendering(): void
    {
        $this->menu->text("You selected something you shouldn't");
    }

    protected function afterRendering(string $argument): void
    {
        //
    }
}
