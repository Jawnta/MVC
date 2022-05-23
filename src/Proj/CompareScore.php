<?php

namespace App\Proj;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class for comparing hand scores
 */
class CompareScore
{
    /**
     * @param SessionInterface $session
     * @param string $character
     * @return array<object>
     */
    public function getHand(SessionInterface $session, string $character): array
    {
        $player = $session->get($character);
        return $player->hand;
    }


    /**
     * @param array<object> $hand
     * @param int $occurrencesToCompare
     * @return int
     * Full house
     * Four of a kind
     * Three of a kind
     */
    public function getHighestValueWithOccurrences(array $hand, int $occurrencesToCompare): int
    {
        $valueArr = [];
        foreach ($hand as $card) {
            array_push($valueArr, $card->value);
        }
        $occurrences = array_count_values($valueArr);

        foreach ($occurrences as $key => $value) {
            if ($value == $occurrencesToCompare) {
                return $key;
            }
        }
        return -1;
    }

    /**
     * @param SessionInterface $session
     * @return string
     * Straight flush
     * Flush
     * Straight
     */
    private function compareHandWithNoOccurrences(SessionInterface $session): string
    {
        $rule = new Rules();
        $playerHighCard = $rule->getHighCard($session, "pokerPlayer");
        $npcHighCard = $rule->getHighCard($session, "pokerNpc");
        if ($playerHighCard->value > $npcHighCard->value) {
            return "player";
        } elseif ($playerHighCard->value < $npcHighCard->value) {
            return "npc";
        }
        return "draw";
    }

    /**
     * @param array<object> $hand
     * @return int
     * Get the highest value of pairs.
     */
    private function getHighestValueOfPair(array $hand): int
    {
        $valueArr = [];
        foreach ($hand as $card) {
            array_push($valueArr, $card->value);
        }
        $occurrences = array_count_values($valueArr);
        $result = [];

        foreach ($occurrences as $key => $value) {
            if ($value == 2) {
                array_push($result, $key);
            }
        }
        $result = array_unique($result);
        return max($result);
    }

    /**
     * @param int $playerValue
     * @param int $npcValue
     * @return string
     */
    private function compareValues(int $playerValue, int $npcValue): string
    {
        if ($playerValue > $npcValue) {
            return "player";
        } elseif ($playerValue < $npcValue) {
            return "npc";
        }
        return "draw";
    }


    /**
     * @param SessionInterface $session
     * @return string
     */
    public function compareHands(SessionInterface $session): string
    {
        $rule = new Rules();
        $handRating = [
            "Royal Straight Flush" => 9,
            "Straight Flush" => 8,
            "Four of a kind" => 7,
            "Full House" => 6,
            "Flush" => 5,
            "Straight" => 4,
            "Three of a kind" => 3,
            "Two pair" => 2,
            "Pair" => 1
        ];

        $playerHand = $this->getHand($session, "pokerPlayer");
        $pCurrentStanding = $rule->evaluateAndGetHand($session, 'pokerPlayer');
        $playerHandValue = 0;
        if (array_key_exists($pCurrentStanding, $handRating)) {
            $playerHandValue = $handRating[$pCurrentStanding];
        }

        $npcHand = $this->getHand($session, "pokerNpc");
        $nCurrentStanding = $rule->evaluateAndGetHand($session, 'pokerNpc');
        $npcHandValue = 0;
        if (array_key_exists($nCurrentStanding, $handRating)) {
            $npcHandValue = $handRating[$nCurrentStanding];
        }

        if ($playerHandValue > $npcHandValue) {
            return "player";
        } elseif ($playerHandValue < $npcHandValue) {
            return "npc";
        } elseif ($playerHandValue == $npcHandValue) {
            switch ($pCurrentStanding) {
                case "Royal Straight Flush":
                    return "draw";
                case "Flush":
                case "Straight":
                case "Straight Flush":
                    return $this->compareHandWithNoOccurrences($session);
                case "Four of a kind":
                    $player = $this->getHighestValueWithOccurrences($playerHand, 4);
                    $npc = $this->getHighestValueWithOccurrences($npcHand, 4);
                    return $this->compareValues($player, $npc);
                case "Three of a kind":
                case "Full House":
                    $player = $this->getHighestValueWithOccurrences($playerHand, 3);
                    $npc = $this->getHighestValueWithOccurrences($npcHand, 3);
                    return $this->compareValues($player, $npc);
                case "Pair":
                case "Two pair":
                    $player = $this->getHighestValueOfPair($playerHand);
                    $npc = $this->getHighestValueOfPair($npcHand);
                    return $this->compareValues($player, $npc);
            }

            if ($playerHandValue == 0) {
                $playerHighCard = $rule->getHighCard($session, "pokerPlayer");
                $npcHighCard = $rule->getHighCard($session, "pokerNpc");
                return $this->compareValues($playerHighCard->value, $npcHighCard->value);
            }
        }
        return "Undetermined";
    }
}
