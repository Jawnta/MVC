<?php
//
//declare(strict_types=1);
//
//use App\Card\Card;
//use App\Card\Player;
//use JetBrains\PhpStorm\Pure;
//use PHPUnit\Framework\TestCase;
//use Symfony\Component\HttpFoundation\Session\Session;
//use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
//
//final class BlackJackTest extends TestCase
//{
//    public function testBlackJackCreateObject(): void
//    {
//        $game = new \App\Card\BlackJack();
//        $this->assertEquals(false, $game->started);
//        $this->assertEquals(false, $game->endOfRound);
//    }
//
//    public function testBlackJackSetUpGame(): void
//    {
//        $session = new Session(new MockArraySessionStorage());
//        $preGame = new \App\Card\BlackJack();
//        $session->set('blackJackGame', $preGame);
//        $postGame = $preGame->setupGame($session);
//        $this->assertEquals(2, sizeof($postGame[0]->hand));
//        $this->assertEquals(2, sizeof($postGame[1]->hand));
//        $this->assertEquals(256, sizeof($postGame[2]));
//    }
//
//    public function testBlackJackHit(): void
//    {
//        $session = new Session(new MockArraySessionStorage());
//        $array = [
//            new Card("hearts", 11, "ace"),
//            new Card("hearts", 11, "ace"),
//            new Card("hearts", 11, "ace"),
//            new Card("hearts", 11, "ace"),
//            new Card("hearts", 11, "ace"),
//
//        ];
//
//        $preSetup = new \App\Card\BlackJack();
//        $session->set('blackJackGame', $preSetup);
//        $postSetup = $preSetup->setupGame($session);
//        $session->set('bjDeck', $array);
//        $preSetup->hit($session);
//        $this->assertEquals(3, sizeof($postSetup[1]->hand));
//    }
//
//    public function testBlackJackCheckBlackJack(): void
//    {
//        $session = new Session(new MockArraySessionStorage());
//        $game = new \App\Card\BlackJack();
//        $session->set('blackJackGame', $game);
//        $postSetup = $game->setupGame($session);
//        $postSetup[1]->hand = [
//            new Card("hearts", 11, "ace"),
//            new Card("hearts", 10, "king"),
//            ];
//        $check = $game->checkBlackJack($session, $postSetup[1], "blackJack");
//
//        $this->assertEquals(true, $check);
//        $check = $game->checkBlackJack($session, $postSetup[1], "NotBlackJack");
//        $this->assertEquals(false, $check);
//    }
//
//    public function testDeckIsTooSmall()
//    {
//        $session = new Session(new MockArraySessionStorage());
//        $game = new \App\Card\BlackJack();
//        $session->set('blackJackGame', $game);
//        $postSetup = $game->setupGame($session);
//        $deck = [];
//        $session->set('bjDeck', $deck);
//        $game->roundEnd($session, $postSetup[1], $postSetup[0]);
//        $deck = $session->get('bjDeck');
//        $this->assertEquals(256, sizeof($deck));
//    }
//
//    public function testRoundEndScreen()
//    {
//        $session = new Session(new MockArraySessionStorage());
//        $game = new \App\Card\BlackJack();
//        $session->set('blackJackGame', $game);
//        $game->setupGame($session);
//        $game->roundEndScreen($session, "win");
//        $this->assertEquals(true, $game->endOfRound);
//    }
//
//    public function testDealerDraw()
//    {
//        $session = new Session(new MockArraySessionStorage());
//        $game = new \App\Card\BlackJack();
//        $session->set('blackJackGame', $game);
//        $postSetup = $game->setupGame($session);
//        $game->dealerDraw($session, $postSetup[0]);
//        $this->assertEquals(3, sizeof($postSetup[0]->hand));
//    }
//
//    public function testNewRoundPlayerBlackJack()
//    {
//        $session = new Session(new MockArraySessionStorage());
//        $game = new \App\Card\BlackJack();
//        $session->set('blackJackGame', $game);
//        $postSetup = $game->setupGame($session);
//        $game->dealerDraw($session, $postSetup[0]);
//        $this->assertEquals(3, sizeof($postSetup[0]->hand));
//    }
//
//    public function testBlackJackStayDealer()
//    {
//        $session = new Session(new MockArraySessionStorage());
//        $game = new \App\Card\BlackJack();
//        $session->set('blackJackGame', $game);
//        $postSetup = $game->setupGame($session);
//        $postSetup[0]->hand = [
//            new Card("hearts", 10, "queen"),
//            new Card("hearts", 10, "king")
//        ];
//        $postSetup[0]->score = 20;
//        $postSetup[1]->hand = [
//            new Card("hearts", 5, "five"),
//            new Card("hearts", 9, "nine")
//        ];
//        $postSetup[1]->score = 14;
//        $session->set('dealer', $postSetup[0]);
//        $session->set('player', $postSetup[1]);
//
//        $this->assertEquals('dealer', $game->stay($session));
//    }
//
//    public function testBlackJackStayDraw()
//    {
//        $session = new Session(new MockArraySessionStorage());
//        $game = new \App\Card\BlackJack();
//        $session->set('blackJackGame', $game);
//        $postSetup = $game->setupGame($session);
//        $postSetup[0]->hand = [
//            new Card("hearts", 10, "queen"),
//            new Card("hearts", 10, "king")
//        ];
//        $postSetup[0]->score = 20;
//        $postSetup[1]->hand = [
//            new Card("hearts", 10, "queen"),
//            new Card("hearts", 10, "king")
//        ];
//        $postSetup[1]->score = 20;
//        $session->set('dealer', $postSetup[0]);
//        $session->set('player', $postSetup[1]);
//
//        $this->assertEquals('draw', $game->stay($session));
//    }
//
//    public function testBlackJackStayBust()
//    {
//        $session = new Session(new MockArraySessionStorage());
//        $game = new \App\Card\BlackJack();
//        $session->set('blackJackGame', $game);
//        $postSetup = $game->setupGame($session);
//
//        $postSetup[1]->hand = [
//            new Card("hearts", 10, "queen"),
//            new Card("hearts", 10, "king"),
//            new Card("hearts", 10, "king")
//        ];
//        $postSetup[1]->score = 30;
//        $session->set('player', $postSetup[1]);
//
//        $this->assertEquals('bust', $game->stay($session));
//    }
//
//    public function testBlackJackStayWin()
//    {
//        $session = new Session(new MockArraySessionStorage());
//        $game = new \App\Card\BlackJack();
//        $session->set('blackJackGame', $game);
//        $postSetup = $game->setupGame($session);
//
//        $postSetup[0]->hand = [
//            new Card("hearts", 10, "queen"),
//            new Card("hearts", 10, "king"),
//            new Card("hearts", 10, "king")
//        ];
//        $postSetup[0]->score = 30;
//        $session->set('player', $postSetup[1]);
//
//        $this->assertEquals('win', $game->stay($session));
//    }
//
//    public function testBlackJackStayDealerLessThanPlayer()
//    {
//        $session = new Session(new MockArraySessionStorage());
//        $game = new \App\Card\BlackJack();
//        $session->set('blackJackGame', $game);
//        $postSetup = $game->setupGame($session);
//        $postSetup[0]->hand = [
//            new Card("hearts", 4, "four"),
//            new Card("hearts", 4, "four")
//        ];
//        $postSetup[0]->score = 8;
//        $postSetup[1]->hand = [
//            new Card("hearts", 4, "four"),
//            new Card("hearts", 5, "five")
//        ];
//        $postSetup[1]->score = 9;
//        $session->set('dealer', $postSetup[0]);
//        $session->set('player', $postSetup[1]);
//
//        $this->assertEquals('dealer', $game->stay($session));
//    }
//
//    public function testBlackJackHitBust()
//    {
//        $session = new Session(new MockArraySessionStorage());
//        $game = new \App\Card\BlackJack();
//        $session->set('blackJackGame', $game);
//        $postSetup = $game->setupGame($session);
//        $postSetup[1]->hand = [
//            new Card("hearts", 10, "king"),
//            new Card("hearts", 10, "queen"),
//            new Card("hearts", 5, "five")
//        ];
//
//        $postSetup[1]->score = 25;
//        $session->set('player', $postSetup[1]);
//        $game->hit($session);
//
//        $this->assertEquals('bust', $game->stay($session));
//    }
//
//    public function testUpdateBalanceForBlackJack()
//    {
//        $session = new Session(new MockArraySessionStorage());
//        $game = new \App\Card\BlackJack();
//        $session->set('blackJackGame', $game);
//        $postSetup = $game->setupGame($session);
//        $postSetup[1]->hand = [
//            new Card("hearts", 10, "king"),
//            new Card("hearts", 11, "ace")
//        ];
//
//        $postSetup[1]->score = 21;
//        $postSetup[1]->bet = 500;
//        $postSetup[1]->updateBalance($session, "blackJack");
//        $session->set('player', $postSetup[1]);
//
//        $this->assertEquals(3250, $postSetup[1]->balance);
//    }
//}
