<?php

declare(strict_types=1);


use App\Card\Card;
use App\Proj\Rules;
use App\Proj\Player;
use App\Proj\Npc;
use App\Proj\CompareScore;
use JetBrains\PhpStorm\Pure;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class CompareScoreTest extends TestCase
{
    public function testCompareScoreRoyalStraightFlushDraw(): void
    {
        $array = [
            new Card("hearts", 10, "ten"),
            new Card("hearts", 11, "jack"),
            new Card("hearts", 12, "queen"),
            new Card("hearts", 13, "king"),
            new Card("hearts", 14, "ace")
        ];

        $array2 = [
            new Card("spades", 10, "ten"),
            new Card("spades", 11, "jack"),
            new Card("spades", 12, "queen"),
            new Card("spades", 13, "king"),
            new Card("spades", 14, "ace")
        ];
        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('pokerPlayer', $player);
        $npc = new Npc();
        $npc->hand = $array2;
        $session->set('pokerNpc', $npc);

        $compare = new CompareScore();
        $res = $compare->compareHands($session);
        $this->assertEquals("draw", $res);
    }

    public function testCompareScoreRoyalStraightFlushPlayer(): void
    {
        $array = [
            new Card("hearts", 10, "ten"),
            new Card("hearts", 11, "jack"),
            new Card("hearts", 12, "queen"),
            new Card("hearts", 13, "king"),
            new Card("hearts", 14, "ace")
        ];

        $array2 = [
            new Card("spades", 10, "ten"),
            new Card("spades", 11, "jack"),
            new Card("spades", 12, "queen"),
            new Card("spades", 13, "king"),
            new Card("spades", 9, "nine")
        ];
        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('pokerPlayer', $player);
        $npc = new Npc();
        $npc->hand = $array2;
        $session->set('pokerNpc', $npc);

        $compare = new CompareScore();
        $res = $compare->compareHands($session);
        $this->assertEquals("player", $res);
    }

    public function testCompareScoreRoyalStraightFlushNpc(): void
    {
        $array = [
            new Card("hearts", 10, "ten"),
            new Card("hearts", 11, "jack"),
            new Card("hearts", 12, "queen"),
            new Card("hearts", 13, "king"),
            new Card("hearts", 9, "nine")
        ];

        $array2 = [
            new Card("spades", 10, "ten"),
            new Card("spades", 11, "jack"),
            new Card("spades", 12, "queen"),
            new Card("spades", 13, "king"),
            new Card("spades", 14, "ace")
        ];
        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('pokerPlayer', $player);
        $npc = new Npc();
        $npc->hand = $array2;
        $session->set('pokerNpc', $npc);

        $compare = new CompareScore();
        $res = $compare->compareHands($session);
        $this->assertEquals("npc", $res);
    }

    public function testCompareScoreFlush(): void
    {
        $array = [
            new Card("hearts", 5, "five"),
            new Card("hearts", 3, "three"),
            new Card("hearts", 12, "queen"),
            new Card("hearts", 13, "king"),
            new Card("hearts", 9, "nine")
        ];

        $array2 = [
            new Card("hearts", 4, "four"),
            new Card("spades", 11, "jack"),
            new Card("spades", 12, "queen"),
            new Card("spades", 13, "king"),
            new Card("spades", 14, "ace")
        ];
        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('pokerPlayer', $player);
        $npc = new Npc();
        $npc->hand = $array2;
        $session->set('pokerNpc', $npc);

        $compare = new CompareScore();
        $res = $compare->compareHands($session);
        $this->assertEquals("player", $res);
    }

    public function testCompareScoreStraight(): void
    {
        $array = [
            new Card("hearts", 5, "five"),
            new Card("spades", 3, "three"),
            new Card("hearts", 12, "queen"),
            new Card("hearts", 13, "king"),
            new Card("hearts", 9, "nine")
        ];

        $array2 = [
            new Card("hearts", 4, "four"),
            new Card("spades", 5, "five"),
            new Card("spades", 6, "six"),
            new Card("spades", 7, "seven"),
            new Card("spades", 8, "eight")
        ];
        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('pokerPlayer', $player);
        $npc = new Npc();
        $npc->hand = $array2;
        $session->set('pokerNpc', $npc);

        $compare = new CompareScore();
        $res = $compare->compareHands($session);
        $this->assertEquals("npc", $res);
    }

    public function testCompareScoreStraightFlush(): void
    {
        $array = [
            new Card("hearts", 4, "four"),
            new Card("hearts", 5, "five"),
            new Card("hearts", 6, "six"),
            new Card("hearts", 7, "seven"),
            new Card("hearts", 8, "eight")
        ];

        $array2 = [
            new Card("spades", 4, "four"),
            new Card("spades", 5, "five"),
            new Card("spades", 6, "six"),
            new Card("spades", 7, "seven"),
            new Card("spades", 8, "eight")
        ];

        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('pokerPlayer', $player);
        $npc = new Npc();
        $npc->hand = $array2;
        $session->set('pokerNpc', $npc);

        $compare = new CompareScore();
        $res = $compare->compareHands($session);
        $this->assertEquals("draw", $res);
    }

    public function testCompareScoreFullHouse(): void
    {
        $array = [
            new Card("hearts", 4, "four"),
            new Card("spades", 4, "four"),
            new Card("diamonds", 4, "four"),
            new Card("spades", 8, "eight"),
            new Card("hearts", 8, "eight")
        ];

        $array2 = [
            new Card("hearts", 4, "four"),
            new Card("spades", 4, "four"),
            new Card("diamonds", 4, "four"),
            new Card("spades", 8, "eight"),
            new Card("hearts", 8, "eight")
        ];

        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('pokerPlayer', $player);
        $npc = new Npc();
        $npc->hand = $array2;
        $session->set('pokerNpc', $npc);

        $compare = new CompareScore();
        $res = $compare->compareHands($session);
        $this->assertEquals("draw", $res);
    }

    public function testCompareScoreFourOfAKind(): void
    {
        $array = [
            new Card("hearts", 4, "four"),
            new Card("spades", 4, "four"),
            new Card("diamonds", 4, "four"),
            new Card("clubs", 4, "four"),
            new Card("hearts", 8, "eight")
        ];

        $array2 = [
            new Card("hearts", 4, "four"),
            new Card("spades", 4, "four"),
            new Card("diamonds", 3, "three"),
            new Card("spades", 8, "eight"),
            new Card("hearts", 8, "eight")
        ];

        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('pokerPlayer', $player);
        $npc = new Npc();
        $npc->hand = $array2;
        $session->set('pokerNpc', $npc);

        $compare = new CompareScore();
        $res = $compare->compareHands($session);
        $this->assertEquals("player", $res);
    }

    public function testCompareScoreThreeOfAKind(): void
    {
        $array = [
            new Card("hearts", 4, "four"),
            new Card("spades", 4, "four"),
            new Card("diamonds", 5, "five"),
            new Card("clubs", 4, "four"),
            new Card("hearts", 8, "eight")
        ];

        $array2 = [
            new Card("hearts", 4, "four"),
            new Card("spades", 4, "four"),
            new Card("diamonds", 8, "eight"),
            new Card("spades", 8, "eight"),
            new Card("hearts", 8, "eight")
        ];

        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('pokerPlayer', $player);
        $npc = new Npc();
        $npc->hand = $array2;
        $session->set('pokerNpc', $npc);

        $compare = new CompareScore();
        $res = $compare->compareHands($session);
        $this->assertEquals("npc", $res);
    }

    public function testCompareScoreTwoPairNpc(): void
    {
        $array = [
            new Card("hearts", 4, "four"),
            new Card("spades", 4, "four"),
            new Card("diamonds", 5, "five"),
            new Card("clubs", 5, "five"),
            new Card("hearts", 8, "eight")
        ];

        $array2 = [
            new Card("hearts", 4, "four"),
            new Card("spades", 4, "four"),
            new Card("diamonds", 3, "three"),
            new Card("spades", 8, "eight"),
            new Card("hearts", 8, "eight")
        ];

        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('pokerPlayer', $player);
        $npc = new Npc();
        $npc->hand = $array2;
        $session->set('pokerNpc', $npc);

        $compare = new CompareScore();
        $res = $compare->compareHands($session);
        $this->assertEquals("npc", $res);
    }

    public function testCompareScoreTwoPairDraw(): void
    {
        $array = [
            new Card("hearts", 4, "four"),
            new Card("spades", 4, "four"),
            new Card("diamonds", 3, "three"),
            new Card("spades", 8, "eight"),
            new Card("hearts", 8, "eight")
        ];

        $array2 = [
            new Card("hearts", 4, "four"),
            new Card("spades", 4, "four"),
            new Card("diamonds", 3, "three"),
            new Card("spades", 8, "eight"),
            new Card("hearts", 8, "eight")
        ];

        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('pokerPlayer', $player);
        $npc = new Npc();
        $npc->hand = $array2;
        $session->set('pokerNpc', $npc);

        $compare = new CompareScore();
        $res = $compare->compareHands($session);
        $this->assertEquals("draw", $res);
    }

    public function testCompareScorePairPlayer(): void
    {
        $array = [
            new Card("hearts", 4, "four"),
            new Card("spades", 4, "four"),
            new Card("diamonds", 3, "three"),
            new Card("spades", 5, "five"),
            new Card("hearts", 8, "eight")
        ];

        $array2 = [
            new Card("hearts", 4, "four"),
            new Card("spades", 3, "three"),
            new Card("diamonds", 3, "three"),
            new Card("spades", 8, "eight"),
            new Card("hearts", 9, "nine")
        ];

        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('pokerPlayer', $player);
        $npc = new Npc();
        $npc->hand = $array2;
        $session->set('pokerNpc', $npc);

        $compare = new CompareScore();
        $res = $compare->compareHands($session);
        $this->assertEquals("player", $res);
    }

    public function testCompareScoreNoHandPlayer(): void
    {
        $array = [
            new Card("hearts", 6, "six"),
            new Card("spades", 7, "seven"),
            new Card("diamonds", 8, "eight"),
            new Card("spades", 9, "nine"),
            new Card("hearts", 10, "ten")
        ];

        $array2 = [
            new Card("hearts", 4, "four"),
            new Card("spades", 5, "five"),
            new Card("diamonds", 3, "three"),
            new Card("spades", 8, "eight"),
            new Card("hearts", 9, "nine")
        ];

        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('pokerPlayer', $player);
        $npc = new Npc();
        $npc->hand = $array2;
        $session->set('pokerNpc', $npc);

        $compare = new CompareScore();
        $res = $compare->compareHands($session);
        $this->assertEquals("player", $res);
    }

    public function testCompareScoreNoHandDraw(): void
    {
        $array = [
            new Card("hearts", 6, "six"),
            new Card("spades", 7, "seven"),
            new Card("diamonds", 8, "eight"),
            new Card("spades", 9, "nine"),
            new Card("hearts", 10, "ten")
        ];

        $array2 = [
            new Card("hearts", 6, "six"),
            new Card("spades", 7, "seven"),
            new Card("diamonds", 8, "eight"),
            new Card("spades", 9, "nine"),
            new Card("hearts", 10, "ten")
        ];

        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('pokerPlayer', $player);
        $npc = new Npc();
        $npc->hand = $array2;
        $session->set('pokerNpc', $npc);

        $compare = new CompareScore();
        $res = $compare->compareHands($session);
        $this->assertEquals("draw", $res);
    }

    public function testCompareScoreNoHandNpc(): void
    {
        $array = [
            new Card("hearts", 6, "six"),
            new Card("spades", 7, "seven"),
            new Card("diamonds", 8, "eight"),
            new Card("spades", 9, "nine"),
            new Card("hearts", 4, "four")
        ];

        $array2 = [
            new Card("hearts", 6, "six"),
            new Card("spades", 7, "seven"),
            new Card("diamonds", 8, "eight"),
            new Card("spades", 9, "nine"),
            new Card("hearts", 10, "ten")
        ];


        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->hand = $array;
        $session->set('pokerPlayer', $player);
        $npc = new Npc();
        $npc->hand = $array2;
        $session->set('pokerNpc', $npc);

        $compare = new CompareScore();
        $res = $compare->compareHands($session);
        $this->assertEquals("npc", $res);
    }

}
