<?php

namespace App\Controller;


use App\Proj\Game;
use App\Proj\Npc;
use App\Proj\Player;
use App\Repository\BooksRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProjController extends AbstractController
{
    /**
     * @Route("/proj/home", name="projHome")
     */
    public function pokerHome(SessionInterface $session): Response
    {
        if (!empty($session->get('pokerGame'))) {
            $game = $session->get('pokerGame');
            $player = $session->get('pokerPlayer');
            $npc = $session->get('pokerNpc');
            return $this->render('proj/home.html.twig', [
                    'player' => $player,
                    'started' => $game->started,
                    'npc' => $npc,
                    'session' => $session
                ]
            );
        }
        $player = new Player();
        $npc = new Npc();
        $game = new Game();
        return $this->render('proj/home.html.twig', [
                'player' => $player,
                'started' => $game->started,
                'npc' => $npc,
                'session' => $session
            ]
        );
    }

    /**
     * @Route("/proj/start", name="pokerStart", methods={"POST"})
     */
    public function pokerStart(SessionInterface $session): Response
    {
        $preSetup = new Game();
        $preSetup->preStart($session);
        $preSetup->setStarted(true);
        $session->set('pokerGame', $preSetup);

        return $this->redirectToRoute('projHome');
    }

    /**
     * @Route("/proj/repick", name="pokerRepick", methods={"POST"})
     */
    public function rePick(Request $request, SessionInterface $session): Response
    {
        $player = $session->get('pokerPlayer');
        $player->rePickCards($request, $session);

        return $this->redirectToRoute('projHome');
    }

    /**
     * @Route("/proj/doBet", name="makeBet", methods={"POST"})
     */
    public function doBet(SessionInterface $session, Request $request): Response
    {
        $bet = $request->get('f_bet');
        $player = $session->get('pokerPlayer');
        $player->makeBet($session, $bet);

        $session->set('pokerPlayer', $player);

        return $this->redirectToRoute('projHome');
    }

    /**
     * @Route("/proj/newRound", name="newRound", methods={"POST"})
     */
    public function newRound(SessionInterface $session): Response
    {
        $game = $session->get('pokerGame');
        $game->newRound($session);

        return $this->redirectToRoute('projHome');
    }

    /**
     * @Route("/proj/reset", name="pokerReset")
     */
    public function pokerReset(SessionInterface $session): Response
    {

        $session->set('pokerGame', []);
        $session->set('pokerPlayer', []);


        return $this->redirectToRoute('projHome');
    }



}
