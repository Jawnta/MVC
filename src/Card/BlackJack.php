<?php

namespace App\Card;

use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BlackJack
{
    public bool $started;
    public bool $endOfRound;

    public function __construct($started = false, $endOfRound = false)
    {
        $this->started = $started;
        $this->endOfRound = $endOfRound;
    }

    public function setupGame(SessionInterface $session): array
    {
        $newDeck = new Deck();
        $newDeck->blackJackDeck($session);
        $player = new Player();
        $dealer = new Dealer();
        $session->set('player', $player);
        $session->set('dealer', $dealer);

        $this->newRound($session, $player, $dealer);
        $sessDeck = $session->get('bjDeck');
        return [$dealer, $player, $sessDeck];
    }

    public function newRound(SessionInterface $session, $player, $dealer)
    {
        $this->playerDraw($session, $player);
        $this->dealerDraw($session, $dealer);
        $this->score($dealer);
        $this->score($player);
        if ($player->score == 21) {
            $player->balance += $player->bet * 2.5;
            $player->bet = 0;
            $session->set('player', $player);
            $this->roundEndScreen($session, "blackJack");
            $this->roundEnd($session, $player, $dealer);
        }
    }

    public function dealerDraw(SessionInterface $session, $dealer)
    {
        $deck = $session->get('bjDeck');

        if ($dealer->hand == []) {
            $dealer->hand[] = array_pop($deck);
            $dealer->hand[0]->back = true;
            $dealer->hand[] = array_pop($deck);
            $dealer->hand[1]->back = true;
        } else {
            $dealer->hand[] = array_pop($deck);
        }

        $session->set('bjDeck', $deck);
        $session->set('dealer', $dealer);
    }

    public function playerDraw(SessionInterface $session, $player)
    {
        $deck = $session->get('bjDeck');
        if ($player->hand == []) {
            $player->hand[] = array_pop($deck);
            $player->hand[0]->back = true;
            $player->hand[] = array_pop($deck);
            $player->hand[1]->back = true;
        } else {
            $player->hand[] = array_pop($deck);
        }
        $this->score($player);
        $session->set('bjDeck', $deck);
        $session->set('player', $player);
    }


    public function score($character)
    {
        $hand = $character->hand;
        $score  = 0;
        $aces = 0;

        for ($i = 0; $i < sizeof($hand); $i++) {
            if ($hand[$i]->title != "ace") {
                $score += $hand[$i]->value;
            } else {
                $aces++;
            }
        }
        for ($x = 0; $x < $aces; $x++) {
            if ($score + 11 > 21) {
                $score += 1;
            } else {
                $score += 11;
            }
        }
        $character->score = $score;
    }

    public function hit(SessionInterface $session)
    {
        $player = $session->get('player');
        $this->playerDraw($session, $player);
        if ($player->score > 21) {
            $this->roundEndScreen($session, "bust");
        }
    }

    public function stay(SessionInterface $session)
    {
        $x = 1;

        while ($x == 1) {
            $dealer = $session->get('dealer');
            $player = $session->get('player');
            $dealer->hand[0]->back = false;
            $dealerScore = $dealer->score;
            $playerScore = $player->score;
            $this->score($dealer);
            if ($dealerScore > $playerScore and $dealerScore <= 21) {
                $x = 2;
                $this->roundEndScreen($session, "dealer");
                return "dealer";
            } elseif ($dealerScore == $playerScore) {
                $x = 2;
                $this->roundEndScreen($session, "draw");
                return "draw";
            } elseif ($playerScore > 21) {
                $x = 2;
                $this->roundEndScreen($session, "bust");
                return "bust";
            } elseif ($dealerScore > 21) {
                $x = 2;
                $this->roundEndScreen($session, "win");
                return "win";
            } elseif ($dealerScore < $playerScore) {
                $this->dealerDraw($session, $dealer);
                $this->score($dealer);
                $session->set('dealer', $dealer);
            }
        }
    }
    public function roundEndScreen(SessionInterface $session, $result)
    {
        $bj = $session->get('blackJackGame');
        $bj->endOfRound = true;
        $player = $session->get('player');
        $player->updateBalance($session, $result);
        return $result;
    }

    public function roundEnd(SessionInterface $session, $player, $dealer)
    {
        $deck = $session->get('bjDeck');

        if (sizeof($deck) < 52) {
            $newDeck = new Deck();
            $newDeck->blackJackDeck($session);
        }
        $player->hand = [];
        $dealer->hand = [];
        $session->set('player', $player);
        $session->set('dealer', $dealer);
        $this->newRound($session, $player, $dealer);
    }

    public function checkBlackJack(SessionInterface $session, $player, $result): bool
    {
        $dealer = $session->get('dealer');
        if ($result == "blackJack") {
            $this->roundEnd($session, $player, $dealer);
            return true;
        }
        return false;
    }
}
