<?php

namespace App\Controller;

use App\Card\BlackJack;
use App\Card\Deck;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    /**
     * @Route("/card/game/game", name="game")
     */
    public function game(): Response
    {
        return $this->render('card/game/game.html.twig');
    }

    /**
     * @Route("/card/game/doc", name="doc")
     */
    public function doc(): Response
    {
        return $this->render('card/game/doc.html.twig');
    }
    /**
     * @Route("/card/game/blackJack", name="blackJack")
     */
    public function blackJack(SessionInterface $session): Response
    {
        if (!empty($session->get('bjDeck'))) {
            $dealer = $session->get('dealer');
            $player = $session->get('player');
            $sessDeck = $session->get('bjDeck');
            $data = [$dealer, $player, $sessDeck];
            $blackJack = $session->get('blackJackGame');

            return $this->render('card/game/blackJack.html.twig', ['deck' => $data, 'game' => $blackJack, 'player' => $player]);
        }
        $blackJack = new BlackJack();
        $session->set('blackJackGame', $blackJack);
        $player = $session->get('player');

        return $this->render('card/game/blackJack.html.twig', ['game' => $blackJack, 'player' => $player]);
    }
    /**
     * @Route("/card/game/blackJackHit", name="hit", methods={"POST"})
     */
    public function hit(SessionInterface $session, Request $request): RedirectResponse
    {
        $request->get('hitMe');
        $blackJack = $session->get('blackJack');
        $blackJack->hit($session);
        return $this->redirect('blackJack');
    }

    /**
     * @Route("/card/game/blackJackStay", name="stay", methods={"POST"})
     */
    public function stay(SessionInterface $session, Request $request): RedirectResponse
    {
        $request->get('stayMe');
        $blackJack = $session->get('blackJack');
        $blackJack->stay($session);
        return $this->redirect('blackJack');
    }

    /**
     * @Route("/card/game/startGame", name="start", methods={"POST"})
     */
    public function start(SessionInterface $session): RedirectResponse
    {
        $blackJack = $session->get('blackJackGame');
        $blackJack->setupGame($session);
        $blackJack->started = true;
        $session->get('bjDeck');
        $session->set('blackJack', $blackJack);
        return $this->redirect('blackJack');
    }

    /**
     * @Route("/card/game/newRound", name="newRound", methods={"POST"})
     */
    public function newRound(SessionInterface $session, Request $request): RedirectResponse
    {
        $request->get('newRound');
        $player = $session->get('player');
        $dealer = $session->get('dealer');
        $blackJack = $session->get('blackJack');
        $blackJack->roundEnd($session, $player, $dealer);
        $bj = $session->get('blackJackGame');
        $bj->endOfRound = false;
        return $this->redirect('blackJack');
    }
    /**
     * @Route("/card/game/bet", name="bet", methods={"POST"})
     */
    public function bet(SessionInterface $session, Request $request): RedirectResponse
    {
        $player = $session->get('player');
        $dealer = $session->get('dealer');
        $bj = $session->get('blackJackGame');
        $player->playerBet($session, $request);
        $player->hand[0]->back = false;
        $player->hand[1]->back = false;
        $dealer->hand[1]->back = false;

        if ($player->score == 21) {
            $bj->checkBlackJack($session, $player, "blackJack");
        }
        return $this->redirect('blackJack');
    }
}
