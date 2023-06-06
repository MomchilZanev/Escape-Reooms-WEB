<?php
class Riddle
{
    private $id;
    public $type;
    public $language;
    public $task;
    public $solution;
    public $image;

    public function __construct($id = null, $type = "other", $language = "en", $task = null, $solution = null, $image = null)
    {
        $this->id = $id;
        $this->type = $type;
        $this->language = $language;
        $this->task = $task;
        $this->solution = $solution;
        $this->image = $image;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }
}
?>