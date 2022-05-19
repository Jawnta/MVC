<?php

declare(strict_types=1);


use App\Card\Card;
use App\Proj\Rules;
use App\Proj\Player;
use JetBrains\PhpStorm\Pure;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class RulesTest extends TestCase
{
    public function testRulesGetHighCard(): void
    {
        $array = [
            new Card("hearts", 2, "two"),
            new Card("diamonds", 2, "two"),
            new Card("clubs", 3, "three"),
            new Card("hearts", 4, "four"),
            new Card("hearts", 5, "five")
        ];
        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('testPlayer', $player);

        $rules = new Rules();
        $res = $rules->getHighCard($session, 'testPlayer');
        $this->assertEquals("five", $res->title);
    }

    public function testRulesIsPair(): void
    {
        $array = [
            new Card("hearts", 2, "two"),
            new Card("diamonds", 2, "two"),
            new Card("clubs", 3, "three"),
            new Card("hearts", 4, "four"),
            new Card("hearts", 5, "five")
        ];
        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('testPlayer', $player);

        $rules = new Rules();
        $res = $rules->isPair($session, 'testPlayer');
        $this->assertEquals(true, $res);
    }

    public function testRulesIsTwoPair(): void
    {
        $array = [
            new Card("hearts", 2, "two"),
            new Card("diamonds", 2, "two"),
            new Card("clubs", 3, "three"),
            new Card("hearts", 3, "three"),
            new Card("hearts", 5, "five")
        ];
        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('testPlayer', $player);

        $rules = new Rules();
        $res = $rules->isTwoPair($session, 'testPlayer');
        $this->assertEquals(true, $res);
    }

    public function testRulesIsThreeOfAKind(): void
    {
        $array = [
            new Card("hearts", 2, "two"),
            new Card("diamonds", 2, "two"),
            new Card("clubs", 2, "two"),
            new Card("hearts", 4, "four"),
            new Card("hearts", 5, "five")
            ];
        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('testPlayer', $player);

        $rules = new Rules();
        $res = $rules->isThreeOfAKind($session, 'testPlayer');
        $this->assertEquals(true, $res);
    }

    public function testRulesIsFourOfAKind(): void
    {
        $array = [
            new Card("hearts", 2, "two"),
            new Card("diamonds", 2, "two"),
            new Card("clubs", 2, "two"),
            new Card("spades", 2, "two"),
            new Card("hearts", 5, "five")
        ];
        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('testPlayer', $player);

        $rules = new Rules();
        $res = $rules->isFourOfAKind($session, 'testPlayer');
        $this->assertEquals(true, $res);
    }

    public function testRulesIsFlush(): void
    {
        $array = [
            new Card("hearts", 2, "two"),
            new Card("hearts", 3, "three"),
            new Card("hearts", 4, "four"),
            new Card("hearts", 5, "five"),
            new Card("hearts", 6, "six")
        ];
        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('testPlayer', $player);

        $rules = new Rules();
        $res = $rules->isFlush($session, 'testPlayer');
        $this->assertEquals(true, $res);
    }

    public function testRulesIsFullHouse(): void
    {
        $array = [
            new Card("hearts", 2, "two"),
            new Card("diamonds", 2, "two"),
            new Card("spades", 2, "two"),
            new Card("hearts", 5, "five"),
            new Card("diamonds", 5, "five")
        ];
        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('testPlayer', $player);

        $rules = new Rules();
        $res = $rules->isFullHouse($session, 'testPlayer');
        $this->assertEquals(true, $res);
    }

    public function testRulesIsStraightLowAce(): void
    {
        $array = [
            new Card("hearts", 2, "two"),
            new Card("diamonds", 3, "three"),
            new Card("spades", 4, "four"),
            new Card("hearts", 5, "five"),
            new Card("diamonds", 14, "ace")
        ];
        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('testPlayer', $player);

        $rules = new Rules();
        $res = $rules->isStraight($session, 'testPlayer');
        $this->assertEquals(true, $res);
    }

    public function testRulesIsStraightHighAce(): void
    {
        $array = [
            new Card("hearts", 10, "ten"),
            new Card("diamonds", 11, "jack"),
            new Card("spades", 12, "queen"),
            new Card("hearts", 13, "king"),
            new Card("diamonds", 1, "ace")
        ];

        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('testPlayer', $player);

        $rules = new Rules();
        $res = $rules->isStraight($session, 'testPlayer');
        $this->assertEquals(true, $res);
    }

    public function testRulesIsRoyalStraightFlush(): void
    {
        $array = [
            new Card("hearts", 10, "ten"),
            new Card("hearts", 11, "jack"),
            new Card("hearts", 12, "queen"),
            new Card("hearts", 13, "king"),
            new Card("hearts", 14, "ace")
        ];

        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('testPlayer', $player);

        $rules = new Rules();
        $res = $rules->isStraightFlush($session, 'testPlayer', true);
        $this->assertEquals(true, $res);
    }

    public function testRulesIsStraightFlush(): void
    {
        $array = [
            new Card("hearts", 2, "two"),
            new Card("hearts", 3, "three"),
            new Card("hearts", 4, "four"),
            new Card("hearts", 5, "five"),
            new Card("hearts", 14, "ace")
        ];

        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('testPlayer', $player);

        $rules = new Rules();
        $res = $rules->isStraightFlush($session, 'testPlayer', false);
        $this->assertEquals(true, $res);
    }

    public function testRulesEvaluateHandIsRoyalStraightFlush(): void
    {
        $array = [
            new Card("hearts", 10, "ten"),
            new Card("hearts", 11, "jack"),
            new Card("hearts", 12, "queen"),
            new Card("hearts", 13, "king"),
            new Card("hearts", 14, "ace")
        ];

        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('testPlayer', $player);

        $rules = new Rules();
        $res = $rules->evaluateAndGetHand($session, 'testPlayer');
        $this->assertEquals("Royal Straight Flush", $res);
    }

    public function testRulesEvaluateHandIsStraightFlush(): void
    {
        $array = [
            new Card("hearts", 5, "five"),
            new Card("hearts", 6, "six"),
            new Card("hearts", 7, "seven"),
            new Card("hearts", 8, "eight"),
            new Card("hearts", 9, "nine")
        ];

        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('testPlayer', $player);

        $rules = new Rules();
        $res = $rules->evaluateAndGetHand($session, 'testPlayer');
        $this->assertEquals("Straight Flush", $res);
    }
    public function testRulesEvaluateHandIsFourOfAKind(): void
    {
        $array = [
            new Card("hearts", 5, "five"),
            new Card("spades", 5, "five"),
            new Card("clubs", 5, "five"),
            new Card("diamonds", 5, "five"),
            new Card("hearts", 9, "nine")
        ];

        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('testPlayer', $player);

        $rules = new Rules();
        $res = $rules->evaluateAndGetHand($session, 'testPlayer');
        $this->assertEquals("Four of a kind", $res);
    }

    public function testRulesEvaluateHandIsFullHouse(): void
    {
        $array = [
            new Card("hearts", 5, "five"),
            new Card("spades", 5, "five"),
            new Card("clubs", 5, "five"),
            new Card("diamonds", 4, "four"),
            new Card("hearts", 4, "four")
        ];

        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('testPlayer', $player);

        $rules = new Rules();
        $res = $rules->evaluateAndGetHand($session, 'testPlayer');
        $this->assertEquals("Full House", $res);
    }

    public function testRulesEvaluateHandIsFlush(): void
    {
        $array = [
            new Card("hearts", 5, "five"),
            new Card("hearts", 3, "three"),
            new Card("hearts", 6, "six"),
            new Card("hearts", 7, "seven"),
            new Card("hearts", 9, "nine")
        ];

        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('testPlayer', $player);

        $rules = new Rules();
        $res = $rules->evaluateAndGetHand($session, 'testPlayer');
        $this->assertEquals("Flush", $res);
    }

    public function testRulesEvaluateHandIsStraight(): void
    {
        $array = [
            new Card("hearts", 5, "five"),
            new Card("clubs", 4, "four"),
            new Card("hearts", 6, "six"),
            new Card("hearts", 7, "seven"),
            new Card("hearts", 8, "eight")
        ];

        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('testPlayer', $player);

        $rules = new Rules();
        $res = $rules->evaluateAndGetHand($session, 'testPlayer');
        $this->assertEquals("Straight", $res);
    }

    public function testRulesEvaluateHandIsThreeOfAKind(): void
    {
        $array = [
            new Card("hearts", 5, "five"),
            new Card("clubs", 5, "five"),
            new Card("diamonds", 5, "five"),
            new Card("hearts", 7, "seven"),
            new Card("hearts", 8, "eight")
        ];

        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('testPlayer', $player);

        $rules = new Rules();
        $res = $rules->evaluateAndGetHand($session, 'testPlayer');
        $this->assertEquals("Three of a kind", $res);
    }
    public function testRulesEvaluateHandIsTwoPair(): void
    {
        $array = [
            new Card("hearts", 5, "five"),
            new Card("clubs", 5, "five"),
            new Card("diamonds", 6, "six"),
            new Card("hearts", 6, "six"),
            new Card("hearts", 8, "eight")
        ];

        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('testPlayer', $player);

        $rules = new Rules();
        $res = $rules->evaluateAndGetHand($session, 'testPlayer');
        $this->assertEquals("Two pair", $res);
    }

    public function testRulesEvaluateHandIsPair(): void
    {
        $array = [
            new Card("hearts", 5, "five"),
            new Card("clubs", 5, "five"),
            new Card("diamonds", 7, "seven"),
            new Card("hearts", 6, "six"),
            new Card("hearts", 8, "eight")
        ];

        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('testPlayer', $player);

        $rules = new Rules();
        $res = $rules->evaluateAndGetHand($session, 'testPlayer');
        $this->assertEquals("Pair", $res);
    }


    public function testRulesEvaluateHandHighCard(): void
    {
        $array = [
            new Card("hearts", 5, "five"),
            new Card("clubs", 14, "ace"),
            new Card("diamonds", 9, "nine"),
            new Card("hearts", 2, "two"),
            new Card("hearts", 8, "eight")
        ];

        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('testPlayer', $player);

        $rules = new Rules();
        $res = $rules->evaluateAndGetHand($session, 'testPlayer');
        $this->assertEquals("ace", $res);
    }
}
