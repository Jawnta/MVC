<?php

declare(strict_types=1);


use App\Card\Card;
use App\Proj\Npc;
use App\Proj\Rules;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;



function compare_objects($obj_a, $obj_b)
{
    return $obj_a->value - $obj_b->value;
}

class NpcTest extends TestCase
{

    public function testRulesGetHighCard(): void
    {
        $session = new Session(new MockArraySessionStorage());
        $npc = new Npc();
        $deck = [
            new Card("hearts", 2, "two"),
            new Card("diamonds", 2, "two"),
            new Card("clubs", 3, "three"),
            new Card("hearts", 4, "four"),
            new Card("hearts", 5, "five")
        ];

        $session->set('pokerDeck', $deck);
        $session->set('pokerNpc', $npc);

        $npc->npcDraw($session, $npc);

        $this->assertEquals(array_reverse($deck), $npc->hand);
    }

    public function testRulesNpcRePick(): void
    {
        $session = new Session(new MockArraySessionStorage());
        $npc = new Npc();
        $array = [
            new Card("hearts", 2, "two"),
            new Card("diamonds", 2, "two"),
            new Card("clubs", 3, "three"),
            new Card("hearts", 4, "four"),
            new Card("hearts", 5, "five")
        ];

        $deck = [
            new Card("hearts", 6, "six"),
            new Card("diamonds", 7, "seven"),
            new Card("clubs", 8, "eight"),
            new Card("hearts", 9, "nine"),
            new Card("hearts", 10, "ten")
        ];

        $npc->hand = $array;

        $session->set('pokerDeck', $deck);
        $session->set('pokerNpc', $npc);

        $npc->npcRePick($session);

        $diff = array_udiff($deck, $array, 'compare_objects');

        $this->assertEquals(5, sizeof($diff));
    }

    public function testRulesNpcRePickAce(): void
    {
        $session = new Session(new MockArraySessionStorage());
        $npc = new Npc();
        $array = [
            new Card("hearts", 2, "two"),
            new Card("diamonds", 9, "nine"),
            new Card("clubs", 3, "three"),
            new Card("hearts", 4, "four"),
            new Card("hearts", 14, "ace")
        ];

        $deck = [
            new Card("hearts", 6, "six"),
            new Card("diamonds", 6, "six"),
            new Card("clubs", 6, "six"),
            new Card("hearts", 9, "nine"),
            new Card("hearts", 10, "ten")
        ];

        $npc->hand = $array;

        $session->set('pokerDeck', $deck);
        $session->set('pokerNpc', $npc);

        $npc->npcRePick($session);


        $diff = array_udiff($deck, $array, 'compare_objects');

        $this->assertEquals(4, sizeof($diff));
    }

}
