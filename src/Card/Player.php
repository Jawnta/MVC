<?php

namespace App\Card;

use Symfony\Component\HttpFoundation\Request;
use JetBrains\PhpStorm\Pure;
use mysql_xdevapi\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Player class for blackjack game
 * @SuppressWarnings(PHPMD)
 */
class Player
{
    public array $hand;
    public int $score;
    public int $balance;
    public int $bet;

    /**
     * Constructor which holds properties of hand, score, balance and bet.
     */
    public function __construct($hand = [], $score = 0, $balance = 2000, $bet = 0)
    {
        $this->hand = $hand;
        $this->score = $score;
        $this->balance = $balance;
        $this->bet = $bet;
    }


    public function playerBet(SessionInterface $session, Request $request)
    {
        $bet = $request->get('theBet');
        $player = $session->get('player');
        $player->bet = $bet;
        $player->balance -= $bet;
    }

    /**
     * updateBalance calculates new player balance after a round ends
     */
    public function updateBalance(SessionInterface $session, $result): string
    {
        if ($result == "blackJack") {
            $this->balance += $this->bet * 2.5;
            $this->bet = 0;
            $session->set('player', $this);
            return "BlackJack!";
        } elseif ($result == "win") {
            $this->balance += $this->bet * 2;
            $this->bet = 0;
            $session->set('player', $this);
            return "Winner!";
        } elseif ($result == "bust") {
            $session->set('player', $this);
            $this->bet = 0;

            return "Bust";
        } elseif ($result == 'draw') {
            $session->set('player', $this);
            $this->balance += ($this->bet / 2);
            $this->bet = 0;
            return "Draw";
        } else {
            $session->set('player', $this);
            $this->bet = 0;
            $this->balance -= $this->bet;
            return "Dealer wins";
        }
    }
}
