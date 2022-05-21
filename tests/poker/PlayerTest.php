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
//    public function rePickCards(Request $request, Session $session)
//    {
//        $rule = New Rules();
//        $deck = $session->get('pokerDeck');
//        $player = $session->get('pokerPlayer');
//        foreach ($request->request->keys() as $card) {
//            $index = $this->getCardIndexByImgPath($card, $player);
//            array_splice($player->hand, $index, 1);
//            $player->hand[] = array_pop($deck);
//
//        }
//
//        $player->replaced = true;
//        $player->currentHand = $rule->evaluateAndGetHand($session, "pokerPlayer");
//
//        $session->set('pokerPlayer', $player);
//        $session->set('pokerDeck', $deck);
//    }
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

        $request = new Request(["two_of_hearts_svg"]);
        $card = [new Card("hearts", 14, "ace")];
        $session->set('pokerDeck', $card);

        $player->rePickCards($request, $session);

        $player = $session->get('pokerPlayer');

        $this->assertEquals(true, $player->replaced);

    }

}
