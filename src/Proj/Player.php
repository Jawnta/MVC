<?php

namespace App\Proj;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Player class for blackjack game
 */
class Player
{
    public array $hand;
    public int $balance;
    public int $bet;
    public bool $replaced;
    public string $currentHand;

    /**
     * Constructor which holds properties below.
     * @param array $hand
     * @param int $balance
     * @param int $bet
     * @param bool $replaced
     * @param string $currentHand
     */
    public function __construct(
        array $hand = [],
        int $balance = 2000,
        int $bet = 0,
        bool $replaced = false,
        string $currentHand = ""
    )
    {
        $this->hand = $hand;
        $this->balance = $balance;
        $this->bet = $bet;
        $this->replaced = $replaced;
        $this->currentHand = $currentHand;
    }


    /**
     * @param SessionInterface $session
     * @param Request $request
     */
    public function playerBet(SessionInterface $session, Request $request)
    {
        $bet = $request->get('playerBet');
        $player = $session->get('pokerPlayer');
        $player->bet = $bet;
        $player->balance -= $bet;
    }

    /**
     * @param SessionInterface $session
     * @param $player
     */
    public function playerDraw(SessionInterface $session, $player)
    {
        $deck = $session->get('pokerDeck');
        $cardsToBeDrawn = 5;

        for ($x = 0; $x < $cardsToBeDrawn; $x++) {
            $player->hand[] = array_pop($deck);
            $session->set('pokerDeck', $deck);
        }

        $session->set('pokerPlayer', $player);

    }

    /**
     * @param Request $request
     * @param Session $session
     */
    public function rePickCards(Request $request, Session $session)
    {
        $rule = New Rules();
        $deck = $session->get('pokerDeck');
        $player = $session->get('pokerPlayer');

        foreach ($request->request->keys() as $card) {
            $index = $this->getCardIndexByImgPath($card, $player);
            array_splice($player->hand, $index, 1);
            $player->hand[] = array_pop($deck);

        }

        $player->replaced = true;
        $player->currentHand = $rule->evaluateAndGetHand($session, "pokerPlayer");

        $session->set('pokerPlayer', $player);
        $session->set('pokerDeck', $deck);
    }

    /**
     * @param $cardName
     * @param $player
     * @return int
     */
    private function getCardIndexByImgPath($cardName, $player): int
    {
        $handSize = sizeof($player->hand);
        for ($x = 0; $x < $handSize; $x++) {
            $playerCard = str_replace(".", "_", $player->hand[$x]->imgPath);
            if ($playerCard == $cardName) {
                return $x;
            }
        }
        return -1;
    }

    /**
     * @param SessionInterface $session
     * @param int $bet
     */
    public function setBet(SessionInterface $session, int $bet): void
    {
        $player = $session->get('pokerPlayer');
        $player->bet += $bet;
        $session->set('pokerPlayer', $player);
    }

    /**
     * @param SessionInterface $session
     * @return int
     */
    public function getBet(SessionInterface $session): int
    {
        $player = $session->get('pokerPlayer');
        return $player->bet;
    }

    /**
     * @param SessionInterface $session
     * @param int $balance
     */

    public function setBalance(SessionInterface $session, int $balance)
    {
        $player = $session->get('pokerPlayer');
        $player->balance = $balance;
    }

    /**
     * @return int
     */
    public function getBalance(): int
    {
        return $this->balance;
    }

    /**
     * @param Session $session
     * @param $bet
     */
    public function makeBet(Session $session, $bet)
    {
        $player = $session->get('pokerPlayer');
        $balance = $player->getBalance() - $bet;
        $player->setBalance($session, $balance);
        $player->setBet($session, $bet);

        $session->set('pokerPlayer', $player);
    }

}
