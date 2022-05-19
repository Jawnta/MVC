<?php

namespace App\Proj;

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
        $npc = $session->get('pokerNpc');
        $hand = $npc->hand;
        $currentStanding = $rule->evaluateAndGetHand($session, "pokerNpc");

        $result = array_key_exists($currentStanding, $rules);

        $handSize = sizeof($hand);
        if (!$result) {
            for ($x=0; $x < $handSize; $x++) {
                $index = $this->getCardIndexByImgPath($hand[$x]->title, $npc);
                if (!array_key_exists($hand[$x]->title, $cards)) {
                    array_splice($hand, $index, 1);
                    $hand[] = array_pop($deck);
                }
            }
            $session->set('pokerNpc', $npc);
        }

    }

    /**
     * @param $cardName
     * @param $npc
     * @return int
     */
    private function getCardIndexByImgPath($cardName, $npc): int
    {
        $handSize = sizeof($npc->hand);
        for ($x = 0; $x < $handSize; $x++) {
            $npcCard = $npc->hand[$x]->imgPath;
            if ($npcCard == $cardName) {
                return $x;
            }
        }
        return -1;
    }

}
