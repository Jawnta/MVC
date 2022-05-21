<?php

namespace App\Proj;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class for rules
 */
class Rules
{
    /**
     * @param SessionInterface $session
     * @param $character
     * @return object
     */
    public function getHighCard(SessionInterface $session, $character): object
    {
        $player = $this->getChar($session, $character);
        $hand = $player->hand;
        $this->write_to_console($hand);
        for ($x = 0; $x < 5; $x++) {
            if ($hand[$x]->title == "ace") {
                $hand[$x]->value = 14;
            }
        }
        usort($hand, fn($a, $b) => $a->value - $b->value);


        return $hand[4];
    }

    function write_to_console($data) {

        $console = 'console.log(' . json_encode($data) . ');';
        $console = sprintf('<script>%s</script>', $console);
        echo $console;
    }

    /**
     * @param SessionInterface $session
     * @param $character
     * @return bool
     */
    public function isPair(SessionInterface $session, $character): bool
    {
        $player = $this->getChar($session, $character);
        $hand = $player->hand;
        $arr = [];
        foreach ($hand as $card) {
            array_push($arr, $card->title);
        }
        $result = array_count_values($arr);

        foreach ($result as $key => $value) {
            if ($value == 2) {
                return true;
            }
        }
        return false;

    }

    /**
     * @param SessionInterface $session
     * @param $character
     * @return bool
     */
    public function isTwoPair(SessionInterface $session, $character): bool
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
     * @param $character
     * @return bool
     */
    public function isThreeOfAKind(SessionInterface $session, $character): bool
    {
        $player = $this->getChar($session, $character);
        $hand = $player->hand;
        $arr = [];
        foreach ($hand as $card) {
            array_push($arr, $card->title);
        }
        $result = array_count_values($arr);

        foreach ($result as $key => $value) {
            if ($value == 3) {
                return true;
            }
        }
        return false;

    }

    /**
     * @param SessionInterface $session
     * @param $character
     * @return bool
     */
    public function isFourOfAKind(SessionInterface $session, $character): bool
    {
        $player = $this->getChar($session, $character);
        $hand = $player->hand;
        $arr = [];
        foreach ($hand as $card) {
            array_push($arr, $card->title);
        }
        $result = array_count_values($arr);

        foreach ($result as $key => $value) {
            if ($value == 4) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param SessionInterface $session
     * @param $character
     * @return bool
     */
    public function isStraight(SessionInterface $session, $character): bool
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
     * @param $hand
     * @return bool
     */
    public function checkIfStraight($hand): bool
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
     * @param $character
     * @param $shouldBeRoyal
     * @return bool
     */
    public function isStraightFlush(SessionInterface $session, $character, $shouldBeRoyal): bool
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
     * @param $character
     * @return bool
     */
    public function isFlush(SessionInterface $session, $character): bool
    {
        $player = $this->getChar($session, $character);
        $hand = $player->hand;
        $arr = [];
        foreach ($hand as $card) {
            array_push($arr, $card->suit);
        }
        $result = array_count_values($arr);

        foreach ($result as $key => $value) {
            if ($value == 5) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param SessionInterface $session
     * @param $character
     * @return bool
     */
    public function isFullHouse(SessionInterface $session, $character): bool
    {
        $player = $this->getChar($session, $character);
        $hand = $player->hand;
        $arr = [];
        $fullHouse = [];

        foreach ($hand as $card) {
            array_push($arr, $card->title);
        }
        $result = array_count_values($arr);

        foreach ($result as $key => $value) {
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
     * @param $character
     * @return object|array
     */
    public function getChar(SessionInterface $session, $character): object|array
    {
        return $session->get($character);
    }

    /**
     * @param SessionInterface $session
     * @param $character
     * @return string
     */
    public function evaluateAndGetHand(SessionInterface $session, $character): string
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
            if ($value)  {
                return $key;
            }
        }
        $card = $this->getHighCard($session, $character);
        return $card->title;
    }

}
