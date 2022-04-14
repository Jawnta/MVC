<?php

namespace App\Card;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class DeckWithJokers extends Deck
{
    public function newDeckWithJokers(): array
    {
        $newDeck = $this->newDeck();

        array_push($newDeck, new Card("red", 100, "joker"));
        array_push($newDeck, new Card("black", 100, "joker"));

        return $newDeck;
    }
}
