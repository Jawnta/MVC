<?php

namespace App\Proj;

use App\Proj\Player;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


/**
 * Class for Game
 */
class Game extends AbstractController
{
    public bool $started = false;

    /**
     * @param false $started
     */
    public function __construct(bool $started=false) {
        $this->started = $started;
    }

    /**
     * @param SessionInterface $session
     */
    public function preStart(SessionInterface $session) {
        $rule = New Rules();
        $deck = new PokerDeck();
        $shuffled = $deck->shuffleDeck();
        $session->set('pokerDeck', $shuffled);
        $player = new Player();
        $player->playerDraw($session, $player);
        $player->currentHand = $rule->evaluateAndGetHand($session, "pokerPlayer");
        $npc = new Npc();
        $npc->npcDraw($session, $npc);
        $npc->currentHand = $rule->evaluateAndGetHand($session, "pokerNpc");
        $session->set('pokerNpc', $npc);
        $session->set('pokerPlayer', $player);

    }

    /**
     * @param bool $started
     * @return bool
     */
    public function setStarted(bool $started): bool
    {
        $this->started = $started;
        return true;
    }

    public function roundEnd(SessionInterface $session) {

    }

    /**
     * @param SessionInterface $session
     *
     */
    public function newRound(SessionInterface $session) {
        $player = $session->get('pokerPlayer');
        $player->hand = [];
        $player->replaced = false;
        $player->bet = 0;
        $rule = new Rules();

        $npc = $session->get('pokerNpc');
        $npc->hand = [];

        $deck = new PokerDeck();
        $shuffled = $deck->shuffleDeck();

        $player->playerDraw($session, $player);
        $player->currentHand = $rule->evaluateAndGetHand($session, "pokerPlayer");
        $npc->npcDraw($session, $npc);
        $npc->currentHand = $rule->evaluateAndGetHand($session, "pokerNpc");

        $session->set('pokerPlayer', $player);
        $session->set('pokerNpc', $npc);
        $session->set('pokerDeck', $shuffled);

    }
}
