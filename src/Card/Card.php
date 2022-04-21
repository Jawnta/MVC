<?php

namespace App\Card;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Card
{
    public int $value;
    public string $suit;
    public string $title;
    public string $imgPath;
    public bool $back;
    public string $backImgPath;

    public function __construct($suit, $value, $title, $back = false)
    {
        $this->suit = $suit;
        $this->value = $value;
        $this->title = $title;
        $this->imgPath = $title . "_of_" . $suit . ".svg";
        $this->back = $back;
        $this->backImgPath = "card_back.svg";
    }
}
