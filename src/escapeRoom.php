<?php
require_once "db.php";
require_once "riddle.php";

class EscapeRoom
{
    private $id;
    private $name;
    private $language;
    private $difficulty;
    private $timeLimit;
    private $minPlayers;
    private $maxPlayers;
    private $image;

    private $db;

    public function __construct($name, $language = "en", $difficulty = 1, $timeLimit = 60, $minPlayers = 1, $maxPlayers = 10, $image = null, $id = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->language = $language;
        $this->difficulty = $difficulty;
        $this->timeLimit = $timeLimit;
        $this->minPlayers = $minPlayers;
        $this->maxPlayers = $maxPlayers;
        $this->image = $image;

        $this->db = new Database();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function getDifficulty()
    {
        return $this->difficulty;
    }

    public function getTimeLimit()
    {
        return $this->timeLimit;
    }

    public function getMinPlayer()
    {
        return $this->minPlayers;
    }

    public function getMaxPlayers()
    {
        return $this->minPlayers;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function save()
    {
        $result = $this->db->insertRoomQuery($this->name, $this->language, $this->difficulty, $this->timeLimit, $this->minPlayers, $this->maxPlayers, $this->image);

        if ($result['success']) {
            $this->id = $result['id'];
        }

        return $result['success'];
    }

    public function toJson()
    {
        $jsonString = json_encode($this->getDTO(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        print_r($jsonString);
    }

    private function getRiddlesInRoomDTO() {
        $riddlesInRoom = Riddle::riddlesToDTO(Riddle::getAllRiddlesInRoom($this->id));
        
        return $riddlesInRoom;
    }

    private function getDTO() // Data Transfer Object
    {
        return [
            'name' => $this->name,
            'language' => $this->language,
            'difficulty' => $this->difficulty,
            'timeLimit' => $this->timeLimit,
            'minPlayers' => $this->minPlayers,
            'maxPlayers' => $this->maxPlayers,
            'image' => $this->image,
            'riddles' => $this->getRiddlesInRoomDTO(),
        ];
    }

    private static function dbResultToArrayOfRooms($dbResult) {
        $rooms = array();
        foreach ($dbResult['data'] as $record) {
            $room = new EscapeRoom($record['name'], $record['language'], $record['difficulty'], $record['timeLimit'], $record['minPlayers'], $record['maxPlayers'], $record['image'], $record['id']);
            array_push($rooms, $room);
        }

        return $rooms;
    }

    public static function getAllRooms()
    {
        $db = new Database();
        $result = $db->selectRoomsQuery();

        if (!$result['success']) {
            return null;
        }

        return EscapeRoom::dbResultToArrayOfRooms($result);
    }

    public static function roomsToJson($rooms)
    {
        $jsonResult = null;
        foreach ($rooms as $room) {
            $jsonResult .= $room->toJson() . PHP_EOL;
        }

        return $jsonResult;
    }
}

function testEscapeRoomToJson()
{
    $testRoom = new EscapeRoom('Стая', 'bg', 4, 67, 4, 7, 'няма');

    print_r($testRoom->toJson());
}

function testEscapeRoomSave()
{
    $testRoom = new EscapeRoom('Стая', 'bg', 4, 67, 4, 7, 'няма');

    if ($testRoom->save()) {
        print_r($testRoom->getId());
    }
}

function testGetAllRooms()
{
    print_r(EscapeRoom::getAllRooms());
}

function testRoomsToJson()
{
    print_r(EscapeRoom::roomsToJson(EscapeRoom::getAllRooms()));
}

function escapeRoomMain()
{
    # testEscapeRoomSave();
    # testEscapeRoomToJson();
    # testGetAllRooms();
    # testRoomsToJson();
}

escapeRoomMain();
?>