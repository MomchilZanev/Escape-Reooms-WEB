<?php
class EscapeRoom
{
    public $id;
    public $name;
    public $language;
    public $difficulty;
    public $timeLimit;
    public $minPlayers;
    public $maxPlayers;
    public $image;
    public $riddles;


    public function __construct($id = null, $name = null, $language = 'en', $difficulty = 1, $timeLimit = 60, $minPlayers = 1, $maxPlayers = 10, $image = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->language = $language;
        $this->difficulty = $difficulty;
        $this->timeLimit = $timeLimit;
        $this->minPlayers = $minPlayers;
        $this->maxPlayers = $maxPlayers;
        $this->image = $image;
        $this->riddles = array();
    }
}
?>