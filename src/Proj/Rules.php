<?php

namespace App\Proj;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class for rules
 * @SuppressWarnings(ShortVariable)
 */
class Rules
{
    /**
     * @param SessionInterface $session
     * @param string $character
     * @return object
     */
    public function getHighCard(SessionInterface $session, string $character): object
    {
        $player = $this->getChar($session, $character);
        $hand = $player->hand;

        foreach ($hand as $card) {
            if ($card->title == "ace") {
                $card->value = 14;
            }
        }

        usort($hand, fn($a, $b) => $a->value - $b->value);


        return $hand[4];
    }

    /**
     * @param SessionInterface $session
     * @param string $character
     * @return bool
     */
    public function isPair(SessionInterface $session, string $character): bool
    {
        $player = $this->getChar($session, $character);
        $hand = $player->hand;
        $arr = [];
        foreach ($hand as $card) {
            array_push($arr, $card->title);
        }
        $result = array_count_values($arr);

        foreach (array_values($result) as $value) {
            if ($value == 2) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param SessionInterface $session
     * @param string $character
     * @return bool
     */
    public function isTwoPair(SessionInterface $session, string $character): bool
    {
        $player = $this->getChar($session, $character);
        $hand = $player->hand;
        $arr = [];
        $twoPair = [];
        foreach ($hand as $card) {
            array_push($arr, $card->title);
        }
        $result = array_count_values($arr);

        foreach ($result as $key => $value) {
            if ($value == 2) {
                array_push($twoPair, $key);
            }
        }
        if (sizeof($twoPair) == 2) {
            return true;
        }
        return false;
    }

    /**
     * @param SessionInterface $session
     * @param string $character
     * @return bool
     */
    public function isThreeOfAKind(SessionInterface $session, string $character): bool
    {
        $player = $this->getChar($session, $character);
        $hand = $player->hand;
        $arr = [];
        foreach ($hand as $card) {
            array_push($arr, $card->title);
        }
        $result = array_count_values($arr);

        foreach (array_values($result) as $value) {
            if ($value == 3) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param SessionInterface $session
     * @param string $character
     * @return bool
     */
    public function isFourOfAKind(SessionInterface $session, string $character): bool
    {
        $player = $this->getChar($session, $character);
        $hand = $player->hand;
        $arr = [];
        foreach ($hand as $card) {
            array_push($arr, $card->title);
        }
        $result = array_count_values($arr);

        foreach (array_values($result) as $value) {
            if ($value == 4) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param SessionInterface $session
     * @param string $character
     * @return bool
     */
    public function isStraight(SessionInterface $session, string $character): bool
    {
        $player = $this->getChar($session, $character);
        $hand = $player->hand;
        usort($hand, fn($a, $b) => $a->value - $b->value);
        $isStraight = $this->checkIfStraight($hand);
        $handSize = sizeof($hand);

        if (!$isStraight) {
            $aceCount = 0;
            $aceIndex = -1;

            for ($x = 0; $x < $handSize; $x++) {
                if ($hand[$x]->title == "ace") {
                    $aceCount++;
                    $aceIndex = $x;
                }
            }

            if ($aceIndex != -1) {
                $aceValue = $hand[$aceIndex]->value;
                $hand[$aceIndex]->value = $aceValue == 14 ? 1 : 14;
                usort($hand, fn($a, $b) => $a->value - $b->value);
                $isStraight = $this->checkIfStraight($hand);
            }
        }
        return $isStraight;
    }

    /**
     * @param array<object> $hand
     * @return bool
     */
    public function checkIfStraight(array $hand): bool
    {
        $handSize = sizeof($hand);
        for ($x = 0; $x < $handSize; $x++) {
            $currentValue = $hand[$x]->value;
            $nextValue = null;

            if ($x != $handSize - 1) {
                $nextValue = $hand[$x + 1]->value;

                if ($currentValue + 1 != $nextValue) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @param SessionInterface $session
     * @param string $character
     * @param bool $shouldBeRoyal
     * @return bool
     */
    public function isStraightFlush(SessionInterface $session, string $character, bool $shouldBeRoyal): bool
    {
        $player = $this->getChar($session, $character);
        $isStraight = $this->isStraight($session, $character);
        $hand = $player->hand;

        if (!$isStraight) {
            return false;
        }
        $isFlush = $this->isFlush($session, $character);
        if (!$isFlush) {
            return false;
        }

        $sum = 0;
        foreach ($hand as $value) {
            $sum += $value->value;
        }

        return !$shouldBeRoyal || $sum == 60;
    }

    /**
     * @param SessionInterface $session
     * @param string $character
     * @return bool
     */
    public function isFlush(SessionInterface $session, string $character): bool
    {
        $player = $this->getChar($session, $character);
        $hand = $player->hand;
        $arr = [];
        foreach ($hand as $card) {
            array_push($arr, $card->suit);
        }
        $result = array_count_values($arr);

        foreach (array_values($result) as $value) {
            if ($value == 5) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param SessionInterface $session
     * @param string $character
     * @return bool
     */
    public function isFullHouse(SessionInterface $session, string $character): bool
    {
        $player = $this->getChar($session, $character);
        $hand = $player->hand;
        $arr = [];
        $fullHouse = [];

        foreach ($hand as $card) {
            array_push($arr, $card->title);
        }
        $result = array_count_values($arr);

        foreach (array_keys($result) as $key) {
            if (in_array(2, $result) and in_array(3, $result)) {
                array_push($fullHouse, $key);
            }
        }

        if (sizeof($fullHouse)) {
            return true;
        }

        return false;
    }

    /**
     * @param SessionInterface $session
     * @param string $character
     * @return object
     */
    public function getChar(SessionInterface $session, string $character): object
    {
        return $session->get($character);
    }

    /**
     * @param SessionInterface $session
     * @param string $character
     * @return string
     */
    public function evaluateAndGetHand(SessionInterface $session, string $character): string
    {
        $rules = [
            "Royal Straight Flush" => $this->isStraightFlush($session, $character, true),
            "Straight Flush" => $this->isStraightFlush($session, $character, false),
            "Four of a kind" => $this->isFourOfAKind($session, $character),
            "Full House" => $this->isFullHouse($session, $character),
            "Flush" => $this->isFlush($session, $character),
            "Straight" => $this->isStraight($session, $character),
            "Three of a kind" => $this->isThreeOfAKind($session, $character),
            "Two pair" => $this->isTwoPair($session, $character),
            "Pair" => $this->isPair($session, $character)
        ];

        foreach ($rules as $key => $value) {
            if ($value) {
                return $key;
            }
        }
        $card = $this->getHighCard($session, $character);
        return $card->title;
    }
}
