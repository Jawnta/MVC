<?php

namespace App\Card;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class for a deck + two jokers
 */
class DeckWithJokers extends Deck
{
    /**
     * Creates a new deck + two jokers
     */
    public function newDeckWithJokers(): array
    {
        $newDeck = $this->newDeck();

        array_push($newDeck, new Card("red", 100, "joker"));
        array_push($newDeck, new Card("black", 100, "joker"));

        return $newDeck;
    }
}
