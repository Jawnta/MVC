<?php

namespace App\Card;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Deck class which holds all cards in a deck.
 */
class Deck
{
    /**
     * newDeck creates a deck with 52 cards and 4 suits.
     */
    public function newDeck(): array
    {
        $title = [
            'ace',
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
            'king'
        ];
        $values = [11, 2, 3, 4, 5, 6, 7, 8, 9, 10, 10, 10, 10];
        $suits  = ['spades', 'hearts', 'diamonds', 'clubs'];
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
     */
    public function shuffleDeck(): array
    {
        $cardDeck = new Deck();
        $cards = $cardDeck->newDeck();
        shuffle($cards);
        return $cards;
    }
    /**
     * getCurrentDeck is a getter for the currentDeck
     */
    public function getCurrentDeck(SessionInterface $session): array
    {
        if (empty($session->get('deck'))) {
            return $this->shuffleDeck();
        } else {
            return $session->get('deck');
        }
    }

    /**
     * method to deal x amount of cards to x amount of players
     */
    public function dealCards(SessionInterface $session, $numOfCards, $players): array
    {
        $shuffledDeck = $this->shuffleDeck();
        $session->set('dealDeck', $shuffledDeck);

        $playersWithCards = [];

        for ($i = 0; $i < $players; $i++) {
            $playerWithCards = $this->cardsToPlayer($session, $numOfCards);
            array_push($playersWithCards, $playerWithCards);
        }

        for ($i = 0; $i < ($numOfCards * $players); $i++) {
            array_pop($shuffledDeck);
        }

        return [$playersWithCards, $shuffledDeck];
    }
    /**
     * method for dealing cards to player. Used in dealCards method
     */
    public function cardsToPlayer(SessionInterface $session, $numberOfCards): array
    {
        $deck = $session->get('dealDeck');
        $playerHand = [];
        for ($i = 0; $i < $numberOfCards; $i++) {
            $playerHand[] = array_pop($deck);
        }
        $session->set('dealDeck', $deck);

        return $playerHand;
    }

    /**
     * method for drawing a card
     */
    public function drawCard(SessionInterface $session, $number, $amountOfDrawnCards): array
    {
        $shuffledDeck = $this->getCurrentDeck($session);
        $drawnCards = $session->get('drawnCards');
        if (($number + $amountOfDrawnCards) > 52) {
            return $drawnCards;
        }
        for ($i = 0; $i < $number; $i++) {
            $drawnCards[] = array_pop($shuffledDeck);
        }

        $session->set('deck', $shuffledDeck);
        $session->set('drawnCards', $drawnCards);

        $drawnCards = $session->get('drawnCards');
        $shuffledDeck = $session->get('deck');

        return [$drawnCards, $shuffledDeck];
    }

    /**
     * method for creating a deck for blackjack which contains 5 * 52 card deck.
     */
    public function blackJackDeck(SessionInterface $session)
    {
        $amount = 5;
        $deck = [];
        $newDeck = $this->shuffleDeck();
        for ($i = 0; $i < $amount; $i++) {
            foreach ($newDeck as $card) {
                array_push($deck, $card);
            }
        }
        return $session->set('bjDeck', $deck);
    }
}
