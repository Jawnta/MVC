<?php

namespace App\Proj;

use App\Card\Card;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Player class for blackjack game
 */
class Npc
{
    public array $hand;
    public string $currentHand;


    /**
     * @param array $hand
     * @param string $currentHand
     */
    public function __construct(array $hand = [], string $currentHand = "")
    {
        $this->hand = $hand;
        $this->currentHand = $currentHand;

    }

    /**
     * @param SessionInterface $session
     * @param $npc
     */
    public function npcDraw(SessionInterface $session, $npc)
    {
        $deck = $session->get('pokerDeck');
        $cardsToBeDrawn = 5;

        for ($x = 0; $x < $cardsToBeDrawn; $x++){
            $npc->hand[] = array_pop($deck);
            $session->set('pokerDeck', $deck);
        }

        $session->set('pokerNpc', $npc);
    }

    /**
     * @param SessionInterface $session
     */
    public function npcRePick(SessionInterface $session) {

        $rule = new Rules();
        $rules = [
            "Royal Straight Flush",
            "Straight Flush",
            "Four of a kind",
            "Full House",
            "Flush",
            "Straight",
            "Three of a kind" ,
            "Two pair",
            "Pair"
        ];

        $cards = ["ace", "king", "queen", "jack"];
        $deck = $session->get('pokerDeck');
        $hand = $this->hand;
        $currentStanding = $rule->evaluateAndGetHand($session, "pokerNpc");
        $index = -1;

        $result = in_array($currentStanding, $rules);


        if (!$result) {
            foreach ($this->hand as $card) {
                $index++;
                if (!in_array($card->title, $cards)){
                    unset($this->hand[$index]);
                    $handIndexed = array_values($hand);
                    $hand = $handIndexed;

                    $this->hand[] = array_pop($deck);
                }
            }

        }
        $session->set('pokerNpc', $this);
    }


}
