<?php

namespace App\Controller;

use App\Card\Deck;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeckApiController extends AbstractController
{
    /**
     * @Route("card/api/", name="api", methods={"GET", "HEAD"}))
     */
    public function deck(): Response
    {
        $newDeck = new Deck();
        $myDeck = $newDeck->newDeck();
        return $this->json($myDeck);
    }
}
