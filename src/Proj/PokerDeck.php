<?php

namespace App\Proj;

use App\Card\Card;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Deck class which holds all cards in a deck.
 */
class PokerDeck
{
    /**
     * newDeck creates a deck with 52 cards and 4 suits.
     * @return array<object>
     */
    public function newDeck(): array
    {
        $title = [
            'two',
            'three',
            'four',
            'five',
            'six',
            'seven',
            'eight',
            'nine',
            'ten',
            'jack',
            'queen',
            'king',
            'ace'
        ];
        $values = [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14];
        $suits  = ['hearts', 'diamonds', 'spades',  'clubs'];
        $cards = [];
        $sizeOfSuits = sizeof($suits);
        $sizeOfValues = sizeof($values);
        for ($i = 0; $i < $sizeOfSuits; $i++) {
            for ($x = 0; $x < $sizeOfValues; $x++) {
                array_push($cards, new Card($suits[$i], $values[$x], $title[$x]));
            }
        }

        return $cards;
    }

    /**
     * shuffleDeck creates a new deck then shuffles it.
     * @return array<object>
     */
    public function shuffleDeck(): array
    {
        $cardDeck = new PokerDeck();
        $cards = $cardDeck->newDeck();
        shuffle($cards);
        return $cards;
    }
}
