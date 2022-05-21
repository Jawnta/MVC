<?php

namespace App\Controller;


use App\Card\Card;
use App\Proj\CompareScore;
use App\Proj\Game;
use App\Proj\HighScoreList;
use App\Proj\Npc;
use App\Proj\Player;
use App\Proj\Rules;
use App\Repository\BooksRepository;
use App\Repository\HighScoreRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProjController extends AbstractController
{
    private HighScoreList $highScore;

    /**
     * construct for ease of access of highscore
     */
    public function __construct() {
        $this->highScore = new HighScoreList();
    }
    /**
     * @Route("/proj/home", name="projHome")
     */
    public function pokerHome(SessionInterface $session): Response
    {
        if (!empty($session->get('pokerGame'))) {
            $game = $session->get('pokerGame');
            $player = $session->get('pokerPlayer');
            $npc = $session->get('pokerNpc');
            $result = $session->get('result');
            return $this->render('proj/home.html.twig', [
                    'player' => $player,
                    'started' => $game->started,
                    'npc' => $npc,
                    'session' => $session,
                    'result' => $result
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
                'session' => $session,
                'result' => ""
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
        $compare = new CompareScore();
        $player = $session->get('pokerPlayer');
        $player->rePickCards($request, $session);
        $session->set('pokerPlayer', $player);
        $result = $compare->compareHands($session);
        $session->set('result', $result);

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
        $npc = $session->get('pokerNpc');

        $npc->npcRePick($session);
        $session->set('pokerNpc', $npc);
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
     * @Route("/proj/highScore", name="highScore")
     */
    public function highScore(HighScoreRepository $highScoreRepository): Response
    {
        $highScore = $this->highScore->getHighScoreEntries($highScoreRepository);
        usort($highScore, fn($a, $b) => $b->balance - $a->balance);
        return $this->render('proj/highscore.html.twig', ['highScore' => $highScore]);
    }

    /**
     * @Route("/proj/about", name="aboutProj")
     */
    public function about(): Response
    {
        return $this->render('proj/about.html.twig',);
    }

    /**
     * @Route("/proj/resetDb", name="pokerResetDb")
     */
    public function pokerResetDb(HighScoreRepository $highScoreRepository, ManagerRegistry $doctrine): RedirectResponse
    {
        try {
            $this->highScore->resetDb($highScoreRepository, $doctrine);
        } catch (OptimisticLockException | ORMException $e) {
        }

        return $this->redirectToRoute('highScore');
    }

    /**
     * @Route("/proj/pokerAddToDb", name="pokerAddToDb")
     */
    public function addHighScore(ManagerRegistry $doctrine, Request $request): RedirectResponse
    {
        $values = $request->request->keys();
        $hs = new HighScoreList();
        $name = $request->get('f_name');
        $balance = $request->get('f_balance');
        $hs->addEntry($doctrine, $name, intval($balance));

        return $this->redirectToRoute('highScore');
    }

    /**
     * @Route("/proj/reset", name="pokerReset")
     */
    public function pokerReset(SessionInterface $session): Response
    {

        $session->set('pokerGame', []);
        $session->set('pokerPlayer', []);
        $session->set('result', "");


        return $this->redirectToRoute('projHome');
    }

}
