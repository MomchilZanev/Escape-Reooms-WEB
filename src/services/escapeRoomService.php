<?php
require_once(__DIR__ . '/../data/db.php');
require_once(__DIR__ . '/./riddleService.php');
require_once(__DIR__ . '/../models/escapeRoom.php');
require_once(__DIR__ . '/./serializationService.php');

class EscapeRoomService
{
    private $db;
    private $riddleService;
    private $serializationService;

    public function __construct($db = new Database())
    {
        $this->db = $db;
        $this->riddleService = new RiddleService($db);
        $this->serializationService = new SerializationService();
    }

    public function importFromJson($jsonContents)
    {
        $object = $this->serializationService->getObject($jsonContents);
        if ($object == null) {
            echo 'Invalid JSON contents';
            return false;
        }

        return $this->importFromObj($object);
    }

    private function importFromObj($object)
    {
        if (!array_is_list($object)) {
            $object = array($object);
        }

        foreach ($object as $item) {
            $room = new EscapeRoom(
                null,
                $item['name'],
                $item['language'],
                $item['difficulty'],
                $item['timeLimit'],
                $item['minPlayers'],
                $item['maxPlayers'],
                $item['image']
            );
            if (!$this->addRoom($room)) {
                return false;
            }

            $riddles = $item['riddles'];
            if ($riddles) {
                if (!$this->riddleService->importFromObj($riddles, $room->id)) {
                    return false;
                }
            }
        }
    }

    public function addRoomJson($roomJson)
    {
        $object = $this->serializationService->getObject($roomJson);
        if ($object == null) {
            echo 'Invalid escape room data';
            return false;
        }

        $room = new EscapeRoom(
            null,
            $object['name'],
            $object['language'],
            $object['difficulty'],
            $object['timeLimit'],
            $object['minPlayers'],
            $object['maxPlayers'],
            $object['image']
        );

        if (!$this->addRoom($room)) {
            return false;
        }

        $riddleIds = $object['riddleIds'];
        return $this->addExistingRiddlesToRoom($room, $riddleIds);
    }

    private function addRoom($room)
    {
        $result = $this->db->insertRoomQuery(
            $room->difficulty,
            $room->timeLimit,
            $room->minPlayers,
            $room->maxPlayers,
            $room->image,
        );

        if ($result['success']) {
            $room->id = $result['id'];

            $result = $this->db->insertRoomTranslationQuery(
                $room->id,
                $room->language,
                $room->name
            );
        }

        return $result['success'];
    }

    public function updateRoomJson($roomJson)
    {
        $object = $this->serializationService->getObject($roomJson);
        if ($object == null) {
            echo 'Invalid escape room data';
            return false;
        }

        $room = new EscapeRoom(
            $object['id'],
            $object['name'],
            $object['language'],
            $object['difficulty'],
            $object['timeLimit'],
            $object['minPlayers'],
            $object['maxPlayers'],
            $object['image']
        );
        if (!$this->updateRoom($room)) {
            return false;
        }

        $this->deleteAllRiddlesInRoom($room);
        $riddleIds = $object['riddleIds'];
        return $this->addExistingRiddlesToRoom($room, $riddleIds);
    }

    private function updateRoom($room)
    {
        if (!$this->roomExists($room->id)) {
            echo 'Escape room does not exist';
            return;
        }

        $result = $this->db->updateRoomQuery(
            $room->id,
            $room->difficulty,
            $room->timeLimit,
            $room->minPlayers,
            $room->maxPlayers,
            $room->image,
        );

        if ($result['success']) {
            return $this->translateRoom($room);
        }

        return false;
    }

    private function addExistingRiddlesToRoom($room, $riddleIds)
    {
        if (!array_is_list($riddleIds)) {
            return false;
        }

        foreach ($riddleIds as $riddleId) {
            if (!$this->riddleService->addRoomRiddle($room->id, $riddleId)) {
                return false;
            }
        }

        return true;
    }

    private function deleteAllRiddlesInRoom($room)
    {
        if (!$this->roomExists($room->id)) {
            echo 'Escape room does not exist';
            return;
        }

        $result = $this->db->selectRoomRiddlesByRoomQuery($room->id);

        if ($result['success']) {
            $roomRiddles = $result['data'];
            if (!array_is_list($roomRiddles)) {
                return false;
            }

            foreach ($roomRiddles as $roomRiddle) {
                $this->riddleService->deleteRoomRiddle($roomRiddle['roomId'], $roomRiddle['riddleId']);
            }

            return true;
        }

        return false;
    }

    public function translateRoomJson($roomJson)
    {
        $object = $this->serializationService->getObject($roomJson);
        if ($object == null) {
            echo 'Invalid escape room translation data.';
            return false;
        }

        $room = new EscapeRoom(
            $object['id'],
            $object['name'],
            $object['language']
        );

        return $this->translateRoom($room);
    }

    private function translateRoom($room)
    {
        if (!$this->roomExists($room->id)) {
            echo 'Escape room does not exist';
            return;
        }

        $translation = $this->db->selectRoomTranslationQuery($room->id, $room->language)['data'];

        if ($translation) {
            $result = $this->db->updateRoomTranslationQuery(
                $room->id,
                $room->language,
                $room->name
            );
            return $result['success'];
        } else {
            $result = $this->db->insertRoomTranslationQuery(
                $room->id,
                $room->language,
                $room->name
            );
            return $result['success'];
        }
    }

    public function deleteRoom($id)
    {
        if (!$this->roomExists($id)) {
            echo 'Escape room does not exist';
            return;
        }

        $result = $this->db->deleteRoomQuery($id);

        return $result['success'];
    }

    public function getRoomDetails($id, $language = null)
    {
        if (!$this->roomExists($id)) {
            echo 'Escape room does not exist';
            return;
        }

        $result = $this->db->selectRoomQuery($id);

        if (!$result['success']) {
            return null;
        }

        return $this->dbRecordToRoom($result['data'], $language);
    }

    public function getAllRooms($language = null)
    {
        $result = $this->db->selectRoomsQuery();

        if (!$result['success']) {
            return null;
        }

        return $this->dbResultSetToArrayOfRooms($result, $language);
    }

    public function filterRooms($language = null, $name = null, $minDifficulty = null, $maxDifficulty = null, $minTimeLimit = null, $maxTimeLimit = null, $minPlayers = null, $maxPlayers = null)
    {
        $result = $this->db->selectRoomsWhereQuery($language, $name, $minDifficulty, $maxDifficulty, $minTimeLimit, $maxTimeLimit, $minPlayers, $maxPlayers);

        if (!$result['success']) {
            return null;
        }

        return $this->dbResultSetToArrayOfRooms($result, $language);
    }

    private function dbResultSetToArrayOfRooms($dbResultSet, $language = null)
    {
        $rooms = array();
        foreach ($dbResultSet['data'] as $dbRecord) {
            $room = $this->dbRecordToRoom($dbRecord, $language);
            array_push($rooms, $room);
        }

        return $rooms;
    }

    private function dbRecordToRoom($dbRecord, $language = null)
    {
        $translation = null;
        if (!is_null($language)) {
            $translation = $this->db->selectRoomTranslationQuery($dbRecord['id'], $language)['data'];
        }

        if (!$translation) {
            $translation = $this->db->selectRoomTranslationsQuery($dbRecord['id'])['data'][0];
        }

        $room = new EscapeRoom(
            $dbRecord['id'],
            $translation['name'],
            $translation['language'],
            $dbRecord['difficulty'],
            $dbRecord['timeLimit'],
            $dbRecord['minPlayers'],
            $dbRecord['maxPlayers'],
            $dbRecord['image']
        );
        $room->riddles = $this->riddleService->getAllRiddlesInRoom($room->id, $language);

        return $room;
    }

    private function roomExists($id)
    {
        if ($this->db->selectRoomQuery($id)['data']) {
            return true;
        }
        return false;
    }
}
?>