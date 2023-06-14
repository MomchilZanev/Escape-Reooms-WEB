<?php
require_once(__DIR__ . "/../data/db.php");
require_once(__DIR__ . "/../models/riddle.php");
require_once(__DIR__ . "/./serializationService.php");

class RiddleService
{
    private $db;
    private $serializationService;

    public function __construct($db = new Database())
    {
        $this->db = $db;
        $this->serializationService = new SerializationService();
    }

    public function importFromJson($jsonContents)
    {
        $object = $this->serializationService->getObject($jsonContents);
        if ($object == null) {
            echo "Invalid JSON file.";
            return false;
        }

        return $this->importFromObj($object);
    }

    public function importFromObj($object, $roomId = null)
    {
        if (!is_array($object)) {
            $object = array($object);
        }

        foreach ($object as $item) {
            $riddle = new Riddle(
                null,
                $item['type'],
                $item['language'],
                $item['task'],
                $item['solution'],
                $item['image']
            );
            if (!$this->addRiddle($riddle)) {
                return false;
            }

            if ($roomId) {
                if (!$this->addRoomRiddle($roomId, $riddle->id)) {
                    return false;
                }
            }
        }

        return true;
    }

    public function addRoomRiddle($roomId, $riddleId)
    {
        $result = $this->db->insertRoomRiddleQuery($roomId, $riddleId);

        return $result['success'];
    }

    public function deleteRoomRiddle($roomId, $riddleId)
    {
        $result = $this->db->deleteRoomRiddleQuery($roomId, $riddleId);

        return $result['success'];
    }

    public function addRiddleJson($riddleJson) 
    {
        $object = $this->serializationService->getObject($riddleJson);
        if ($object == null) {
            echo "Invalid riddle data.";
            return false;
        }

        $riddle = new Riddle(
            null,
            $object['type'],
            $object['language'],
            $object['task'],
            $object['solution'],
            $object['image']
        );

        return $this->addRiddle($riddle);
    }

    public function addRiddle($riddle)
    {
        $result = $this->db->insertRiddleQuery(
            $riddle->type,
            $riddle->image
        );

        if ($result['success']) {
            $riddle->id = $result['id'];

            $result = $this->db->insertRiddleTranslationQuery(
                $riddle->id,
                $riddle->language,
                $riddle->task,
                $riddle->solution
            );
        }

        return $result['success'];
    }

    public function updateRiddleJson($riddleJson) 
    {
        $object = $this->serializationService->getObject($riddleJson);
        if ($object == null) {
            echo "Invalid riddle data.";
            return false;
        }

        $riddle = new Riddle(
            $object['id'],
            $object['type'],
            $object['language'],
            $object['task'],
            $object['solution'],
            $object['image']
        );

        return $this->updateRiddle($riddle);
    }

    public function updateRiddle($riddle)
    {
        if (is_null($riddle->id)) {
            return $this->addRiddle($riddle);
        }

        $result = $this->db->updateRiddleQuery(
            $riddle->id,
            $riddle->type,
            $riddle->image
        );

        if ($result['success']) {
            $translation = $this->db->selectRiddleTranslationQuery($riddle->id, $riddle->language)['data'];

            if ($translation) {
                $result = $this->db->updateRiddleTranslationQuery(
                    $riddle->id,
                    $riddle->language,
                    $riddle->task,
                    $riddle->solution
                );
            } else {
                $result = $this->db->insertRiddleTranslationQuery(
                    $riddle->id,
                    $riddle->language,
                    $riddle->task,
                    $riddle->solution
                );
            }
        }

        return $result['success'];
    }

    public function translateRiddleJson($riddleJson) 
    {
        $object = $this->serializationService->getObject($riddleJson);
        if ($object == null) {
            echo "Invalid riddle translation data.";
            return false;
        }

        $riddle = new Riddle(
            $object['id'],
            null,
            $object['language'],
            $object['task'],
            $object['solution']
        );

        return $this->translateRiddle($riddle);
    }

    public function translateRiddle($riddle)
    {
        if (is_null($riddle->id)) {
            return false;
        }

        $translation = $this->db->selectRiddleTranslationQuery($riddle->id, $riddle->language)['data'];

        if ($translation) {
            $result = $this->db->updateRiddleTranslationQuery(
                $riddle->id,
                $riddle->language,
                $riddle->task,
                $riddle->solution,
            );
            return $result['success'];
        } else {
            $result = $this->db->insertRiddleTranslationQuery(
                $riddle->id,
                $riddle->language,
                $riddle->task,
                $riddle->solution,
            );
            return $result['success'];
        }
    }

    public function deleteRiddle($id)
    {
        if (is_null($id)) {
            return false;
        }

        $result = $this->db->deleteRiddleQuery($id);

        return $result['success'];
    }

    public function getRiddleDetails($id, $language)
    {
        $result = $this->db->selectRiddleQuery($id);

        if (!$result['success']) {
            return null;
        }

        return $this->dbRecordToRiddle($result['data'], $language);
    }

    public function getAllRiddles($language)
    {
        $db = new Database();
        $result = $db->selectRiddlesQuery();

        if (!$result['success']) {
            return null;
        }

        return $this->dbResultSetToArrayOfRiddles($result, $language);
    }

    public function getAllRiddlesInRoom($roomId, $language)
    {
        $db = new Database();
        $result = $db->selectRiddlesInRoomQuery($roomId);

        if (!$result['success']) {
            return null;
        }

        return $this->dbResultSetToArrayOfRiddles($result, $language);
    }

    private function dbResultSetToArrayOfRiddles($dbResult, $language)
    {
        $riddles = array();
        foreach ($dbResult['data'] as $dbRecord) {
            $riddle = $this->dbRecordToRiddle($dbRecord, $language);
            array_push($riddles, $riddle);
        }

        return $riddles;
    }

    private function dbRecordToRiddle($dbRecord, $language = null)
    {
        $translation = null;
        if (!is_null($language)) {
            $translation = $this->db->selectRiddleTranslationQuery($dbRecord['id'], $language)['data'];
        }

        if (!$translation) {
            $translation = $this->db->selectRiddleTranslationsQuery($dbRecord['id'])['data'][0];
        }

        $riddle = new Riddle(
            $dbRecord['id'],
            $dbRecord['type'],
            $translation['language'],
            $translation['task'],
            $translation['solution'],
            $dbRecord['image']
        );

        return $riddle;
    }
}
?>