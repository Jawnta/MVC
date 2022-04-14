<?php

namespace App\Card;

use JetBrains\PhpStorm\Pure;

class Player
{
    public array $hand = [];

    public function __construct($hand)
    {
        $this->hand = $hand;
    }
}
