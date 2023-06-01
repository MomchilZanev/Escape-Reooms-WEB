<?php
require_once "db.php";

class Riddle
{
    private $id;
    private $type;
    private $language;
    private $task;
    private $solution;
    private $image;

    private $db;

    public function __construct($type, $language = "en", $task = null, $solution = null, $image = null)
    {
        $this->type = $type;
        $this->language = $language;
        $this->task = $task;
        $this->solution = $solution;
        $this->image = $image;

        $this->db = new Database();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function getTask()
    {
        return $this->task;
    }

    public function getSolution()
    {
        return $this->solution;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function save()
    {
        $result = $this->db->insertRiddleQuery($this->type, $this->language, $this->task, $this->solution, $this->image);

        if ($result['success']) {
            $this->id = $result['id'];
        }

        return $result['success'];
    }

    public function toJson()
    {
        $jsonString = json_encode($this->getDTO(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        return $jsonString;
    }

    private function getDTO() // Data Transfer Object
    {
        return [
            'type' => $this->type,
            'language' => $this->language,
            'task' => $this->task,
            'solution' => $this->solution,
            'image' => $this->image
        ];
    }

    private static function dbResultToArrayOfRiddles($dbResult) {
        $riddles = array();
        foreach ($dbResult['data'] as $record) {
            $riddle = new Riddle($record['type'], $record['language'], $record['task'], $record['solution'], $record['image']);
            array_push($riddles, $riddle);
        }

        return $riddles;
    }

    public static function getAllRiddles()
    {
        $db = new Database();
        $result = $db->selectRiddlesQuery();

        if (!$result['success']) {
            return null;
        }

        return Riddle::dbResultToArrayOfRiddles($result);
    }

    public static function getAllRiddlesInRoom($roomId)
    {
        $db = new Database();
        $result = $db->selectRiddlesInRoomByRoomIdQuery($roomId);

        if (!$result['success']) {
            return null;
        }

        return Riddle::dbResultToArrayOfRiddles($result);
    }

    public static function riddlesToJson($riddles)
    {
        $jsonResult = null;
        foreach ($riddles as $riddle) {
            $jsonResult .= $riddle->toJson() . PHP_EOL;
        }

        return $jsonResult;
    }

    public static function riddlesToDTO($riddles)
    {
        $dtos = array();
        foreach ($riddles as $riddle) {
            array_push($dtos, $riddle->getDTO());
        }

        return $dtos;
    }
}

function testToJson()
{
    $testRiddle = new Riddle('other', 'bg', 'Загадка', 'Решение', 'няма');

    print_r($testRiddle->toJson());
}

function testSave()
{
    $testRiddle = new Riddle('other', 'bg', 'Загадка', 'Решение', 'няма');

    if ($testRiddle->save()) {
        print_r($testRiddle->getId());
    }
}

function testGetAllRiddles()
{
    print_r(Riddle::getAllRiddles());
}

function testGetAllRiddlesInRoom()
{
    print_r(Riddle::getAllRiddlesInRoom(16));
}

function testRiddlesToJson()
{
    print_r(Riddle::riddlesToJson(Riddle::getAllRiddles()));
}

function testRiddlesToDTO()
{
    foreach(Riddle::riddlesToDTO(Riddle::getAllRiddles()) as $dto) {
        print_r(json_encode($dto, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }
}

function riddleMain()
{
    # testSave();
    # testToJson();
    # testGetAllRiddles();
    # testGetAllRiddlesInRoom();
    # testRiddlesToJson();
    # testRiddlesToDTO();
}

riddleMain();
?>