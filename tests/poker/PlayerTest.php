<?php

declare(strict_types=1);


use App\Card\Card;
use App\Proj\Npc;
use App\Proj\Player;
use App\Proj\Rules;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;


class PlayerTest extends TestCase
{

    public function testPlayerBet(): void
    {
        $session = new Session(new MockArraySessionStorage());
        $request = new Request(['playerBet' => 50]);
        $player = new Player();
        $session->set('pokerPlayer', $player);

        $player->playerBet($session, $request);

        $this->assertEquals(1950, $player->balance);
    }

    public function testPlayerRePickCards()
    {
        $session = new Session(new MockArraySessionStorage());
        $array = [
            new Card("hearts", 2, "two"),
            new Card("diamonds", 2, "two"),
            new Card("clubs", 3, "three"),
            new Card("hearts", 4, "four"),
            new Card("hearts", 5, "five")
        ];
        $player = new Player();
        $player->hand = $array;
        $session->set('pokerPlayer', $player);

        $request = new Request([["two_of_hearts_svg"]]);
        $card = [new Card("hearts", 14, "ace")];
        $session->set('pokerDeck', $card);

        $player->rePickCards($request, $session);

        $player = $session->get('pokerPlayer');
        $this->assertEquals(true, $player->replaced);

    }

    public function testPlayerSetBet()
    {
        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $session->set('pokerPlayer', $player);

        $player->setBet($session, 50);
        $this->assertEquals(50, $player->bet);
    }

    public function testPlayerGetBet()
    {
        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $player->bet = 100;
        $session->set('pokerPlayer', $player);

        $bet = $player->getBet($session);

        $this->assertEquals(100, $bet);
    }

    public function testPlayerMakeBet(){
        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $session->set('pokerPlayer', $player);
        $player->makeBet($session, 500);

        $this->assertEquals(500, $player->bet);
        $this->assertEquals(1500, $player->balance);

    }


}
