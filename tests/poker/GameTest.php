<?php

declare(strict_types=1);


use App\Card\Card;
use App\Proj\Game;
use App\Proj\Npc;
use App\Proj\PokerDeck;
use App\Proj\Rules;
use App\Proj\Player;
use JetBrains\PhpStorm\Pure;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class GameTest extends TestCase
{
    public function testGameConstruct(): void
    {
        $game = new Game();

        $this->assertEquals(false, $game->started);
    }

    public function testGamePreStart(): void
    {
        $game = new Game();
        $session = new Session(new MockArraySessionStorage());
        $game->preStart($session);
        $player = $session->get('pokerPlayer');
        $npc = $session->get('pokerNpc');
        $playerHand = $player->hand;
        $npcHand = $npc->hand;
        $this->assertEquals(5, sizeof($playerHand));
        $this->assertEquals(5, sizeof($npcHand));
    }

    public function testGameSetStarted()
    {
        $game = new Game();
        $game->setStarted(true);
        $this->assertEquals(true, $game->started);
    }

    public function testGameNewRoundPlayerWins()
    {
        $game = new Game();
        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $npc = new Npc();
        $deck = new PokerDeck();
        $session->set('pokerDeck', $deck->shuffleDeck());
        $session->set('result', "player");
        $player->bet = 50;
        $player->hand = [1,2,3,4,5];
        $session->set('pokerPlayer', $player);
        $session->set('pokerNpc', $npc);
        $game->newRound($session);

        $this->assertEquals(2100, $player->balance);

    }

    public function testGameNewRoundDraw()
    {
        $game = new Game();
        $session = new Session(new MockArraySessionStorage());
        $player = new Player();
        $npc = new Npc();
        $deck = new PokerDeck();
        $session->set('pokerDeck', $deck->shuffleDeck());
        $session->set('result', "draw");
        $player->bet = 50;
        $player->hand = [1,2,3,4,5];
        $session->set('pokerPlayer', $player);
        $session->set('pokerNpc', $npc);
        $game->newRound($session);

        $this->assertEquals(2025, $player->balance);

    }


}