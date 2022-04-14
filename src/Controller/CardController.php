<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\Deck;
use App\Card\DeckWithJokers;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CardController extends AbstractController
{
    /**
     * @Route("/card/", name="card")
     */
    public function card(): Response
    {
        return $this->render('card/card.html.twig');
    }

    /**
     * @Route("/card/deck", name="deck")
     */
    public function deck(): Response
    {
        $newDeck = new Deck();
        $deck = $newDeck->newDeck();
        $shuffle = $newDeck->shuffleDeck();

        return $this->render('card/deck.html.twig', ['deck' => $deck, 'shuffle' => $shuffle]);
    }
    /**
     * @Route("/card/shuffle", name="shuffle")
     */
    public function shuffle(): Response
    {
        $newDeck = new Deck();
        $shuffle = $newDeck->shuffleDeck();

        return $this->render('card/shuffle.html.twig', ['shuffle' => $shuffle]);
    }

    /**
     * @Route("/card/draw/{number}", name="draw")
     */
    public function draw(SessionInterface $session, $number = 1): Response
    {
        if (empty($session->get('drawnCards'))) {
            $session->set('drawnCards', []);
        }
        $newDeck = new Deck();
        if ($number > 52) {
            return $this->render('card/draw.html.twig', ['data' => ["Noob", "wat"]]);
        }
        if (sizeof($session->get('drawnCards')) < 52) {
            $cardToDraw = $newDeck->drawCard($session, $number);
            return $this->render('card/draw.html.twig', ['data' => $cardToDraw]);
        }

        $drawnCards = $session->get('drawnCards');
        return $this->render('card/draw.html.twig', ['data' => [$drawnCards, 0]]);
    }

    /**
     * @Route("/card/reset", name="reset")
     */
    public function reset(SessionInterface $session): Response
    {
        $session->set('deck', []);
        $session->set('drawnCards', []);


        return $this->render('card/card.html.twig');
    }

    /**
     * @Route("/card/deal/{numberOfCards}/{numberOfPlayers}", name="deal")
     */
    public function deal(SessionInterface $session, $numberOfCards = 0, $numberOfPlayers = 0): Response
    {
        $newDeck = new Deck();
        $playersWithCards = $newDeck->dealCards($session, $numberOfCards, $numberOfPlayers);


        return $this->render(
            'card/deal.html.twig',
            [
                'data' => $playersWithCards,
                'numOfCards' => $numberOfCards,
                'numOfPlayers' => $numberOfPlayers - 1
            ]
        );
    }

    /**
     * @Route("/card/deck2", name="deck2")
     */
    public function deck2(): Response
    {
        $newDeck = new DeckWithJokers();
        $deck = $newDeck->newDeckWithJokers();

        return $this->render('card/deck2.html.twig', ['deck' => $deck]);
    }
}
