<?php

namespace App\Card;

use JetBrains\PhpStorm\Pure;

class Dealer
{
    public array $hand;
    public int $score;

    public function __construct($hand = [], $score = 0)
    {
        $this->hand = $hand;
        $this->score = $score;
    }
}
