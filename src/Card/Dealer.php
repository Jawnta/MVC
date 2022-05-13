<?php

namespace App\Card;

use JetBrains\PhpStorm\Pure;

/**
 * Dealer class
 */
class Dealer
{

    public array $hand;
    public int $score;
    /**
     * construct for dealer which holds the properties for dealer hand and dealer score.
     */
    public function __construct($hand = [], $score = 0)
    {
        $this->hand = $hand;
        $this->score = $score;
    }
}
