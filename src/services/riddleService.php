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
                $item->type,
                $item->language,
                $item->task,
                $item->solution,
                $item->image
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

    public function deleteRiddle($riddleId)
    {
        if (is_null($riddleId)) {
            return false;
        }

        $result = $this->db->deleteRiddleQuery($riddleId);

        return $result['success'];
    }

    public function getAllRiddles($language)
    {
        $db = new Database();
        $result = $db->selectRiddlesQuery();

        if (!$result['success']) {
            return null;
        }

        return $this->dbResultToArrayOfRiddles($result, $language);
    }

    public function getAllRiddlesInRoom($roomId, $language)
    {
        $db = new Database();
        $result = $db->selectRiddlesInRoomQuery($roomId);

        if (!$result['success']) {
            return null;
        }

        return $this->dbResultToArrayOfRiddles($result, $language);
    }

    private function dbResultToArrayOfRiddles($dbResult, $language)
    {
        $riddles = array();
        foreach ($dbResult['data'] as $record) {
            $translation = $this->db->selectRiddleTranslationQuery($record['id'], $language)['data'];

            if (!$translation) {
                $translation = $this->db->selectRiddleTranslationsQuery($record['id'])['data'][0];
            }

            $riddle = new Riddle(
                $record['id'],
                $record['type'],
                $translation['language'],
                $translation['task'],
                $translation['solution'],
                $record['image']
            );
            array_push($riddles, $riddle);
        }

        return $riddles;
    }
}
?>