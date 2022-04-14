<?php

namespace App\Card;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Card
{
    public int $value;
    public string $suit;
    public string $title;
    public string $imgPath;

    public function __construct($suit, $value, $title)
    {
        $this->suit = $suit;
        $this->value = $value;
        $this->title = $title;
        $this->imgPath = $title . "_of_" . $suit . ".svg";
    }
}
