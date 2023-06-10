<?php
class Database
{
    private $connection;

    private $insertRoom;
    private $insertRoomTranslation;
    private $insertRiddle;
    private $insertRiddleTranslation;
    private $insertRoomRiddle;

    private $selectRoom;
    private $selectRoomTranslation;
    private $selectRiddle;
    private $selectRiddleTranslation;
    private $selectRoomRiddlesByRoom;
    private $selectRoomRiddlesByRiddle;

    private $selectRooms;
    private $selectRoomTranslations;
    private $selectRiddles;
    private $selectRiddleTranslations;
    private $selectRoomRiddles;
    private $selectRoomsWhere;

    private $updateRoom;
    private $updateRoomTranslation;
    private $updateRiddle;
    private $updateRiddleTranslation;

    private $deleteRoom;
    private $deleteRoomTranslation;
    private $deleteRiddle;
    private $deleteRiddleTranslation;
    private $deleteRoomRiddle;

    public function __construct()
    {
        $config = parse_ini_file(__DIR__ . "/../../config/config.ini", true);

        $type = $config['db']['dbType'];
        $host = $config['db']['host'];
        $name = $config['db']['dbName'];
        $port = $config['db']['port'];
        $user = $config['db']['user'];
        $password = $config['db']['password'];

        $this->init($type, $host, $name, $port, $user, $password);
    }

    private function init($type, $host, $name, $port, $user, $password)
    {
        try {
            $this->connection = new PDO(
                "$type:host=$host;dbname=$name;port=$port",
                $user,
                $password,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            );

            $this->prepareStatements();
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    private function prepareStatements()
    {

        $sql = "INSERT INTO `escapeRoom`
                    (`difficulty`, `timeLimit`, `minPlayers`, `maxPlayers`, `image`) 
                VALUES 
                    (:difficulty, :timeLimit, :minPlayers, :maxPlayers, :image)";
        $this->insertRoom = $this->connection->prepare($sql);

        $sql = "INSERT INTO `escapeRoomTranslation`
                    (`roomId`, `language`, `name`) 
                VALUES 
                    (:roomId, :language, :name)";
        $this->insertRoomTranslation = $this->connection->prepare($sql);

        $sql = "INSERT INTO `riddle`
                    (`type`, `image`) 
                VALUES 
                    (:type, :image)";
        $this->insertRiddle = $this->connection->prepare($sql);

        $sql = "INSERT INTO `riddleTranslation`
                    (`riddleId`, `language`, `task`, `solution`) 
                VALUES 
                    (:riddleId, :language, :task, :solution)";
        $this->insertRiddleTranslation = $this->connection->prepare($sql);

        $sql = "INSERT INTO roomRiddle
                    (roomId, riddleId)
                VALUES 
                    (:roomId, :riddleId)";
        $this->insertRoomRiddle = $this->connection->prepare($sql);

        $sql = "SELECT * FROM escapeRoom 
                WHERE id = :id";
        $this->selectRoom = $this->connection->prepare($sql);

        $sql = "SELECT * FROM escapeRoomTranslation 
                WHERE roomId = :roomId AND language = :language";
        $this->selectRoomTranslation = $this->connection->prepare($sql);

        $sql = "SELECT * FROM riddle
                WHERE id = :id";
        $this->selectRiddle = $this->connection->prepare($sql);

        $sql = "SELECT * FROM riddleTranslation 
                WHERE riddleId = :riddleId AND language = :language";
        $this->selectRiddleTranslation = $this->connection->prepare($sql);

        $sql = "SELECT * FROM roomRiddle 
                WHERE roomId = :roomId";
        $this->selectRoomRiddlesByRoom = $this->connection->prepare($sql);

        $sql = "SELECT * FROM roomRiddle 
                WHERE riddleId = :riddleId";
        $this->selectRoomRiddlesByRiddle = $this->connection->prepare($sql);

        $sql = "SELECT * FROM escapeRoom";
        $this->selectRooms = $this->connection->prepare($sql);

        $sql = "SELECT * FROM escapeRoomTranslation
                WHERE roomId = :roomId";
        $this->selectRoomTranslations = $this->connection->prepare($sql);

        $sql = "SELECT * FROM riddle";
        $this->selectRiddles = $this->connection->prepare($sql);

        $sql = "SELECT * FROM riddleTranslation
                WHERE riddleId = :riddleId";
        $this->selectRiddleTranslations = $this->connection->prepare($sql);

        $sql = "SELECT * FROM roomRiddle";
        $this->selectRoomRiddles = $this->connection->prepare($sql);

        $sql = "SELECT * FROM escapeRoom AS er
                INNER JOIN escapeRoomTranslation AS ert ON er.id = ert.roomId 
                WHERE (:language IS NULL OR ert.language = :language)
                AND (:minDifficulty IS NULL OR er.difficulty >= :minDifficulty)
                AND (:maxDifficulty IS NULL OR er.difficulty <= :maxDifficulty)
                AND (:minTimeLimit IS NULL OR er.timeLimit >= :minTimeLimit)
                AND (:maxTimeLimit IS NULL OR er.timeLimit <= :maxTimeLimit)
                AND (:minPlayers IS NULL OR er.minPlayers >= :minPlayers)
                AND (:maxPlayers IS NULL OR er.maxPlayers <= :maxPlayers)";
        $this->selectRoomsWhere = $this->connection->prepare($sql);

        $sql = "UPDATE escapeRoom
                SET 
                    difficulty = :difficulty,
                    timeLimit = :timeLimit,
                    minPlayers = :minPlayers,
                    maxPlayers = :maxPlayers,
                    image = :image
                WHERE id = :id";
        $this->updateRoom = $this->connection->prepare($sql);

        $sql = "UPDATE escapeRoomTranslation
                SET name = :name
                WHERE roomId = :roomId AND language = :language";
        $this->updateRoomTranslation = $this->connection->prepare($sql);

        $sql = "UPDATE riddle
                SET 
                    type = :type,
                    image = :image
                WHERE id = :id";
        $this->updateRiddle = $this->connection->prepare($sql);

        $sql = "UPDATE riddleTranslation
                SET 
                    task = :task,
                    solution = :solution
                WHERE riddleId = :riddleId AND language = :language";
        $this->updateRiddleTranslation = $this->connection->prepare($sql);

        $sql = "DELETE FROM escapeRoom
                WHERE id = :id";
        $this->deleteRoom = $this->connection->prepare($sql);

        $sql = "DELETE FROM escapeRoomTranslation
                WHERE roomId = :roomId AND language = :language";
        $this->deleteRoomTranslation = $this->connection->prepare($sql);

        $sql = "DELETE FROM riddle
                WHERE id = :id";
        $this->deleteRiddle = $this->connection->prepare($sql);

        $sql = "DELETE FROM riddleTranslation
                WHERE riddleId = :riddleId AND language = :language";
        $this->deleteRiddleTranslation = $this->connection->prepare($sql);

        $sql = "DELETE FROM roomRiddle
                WHERE roomId = :roomId AND riddleId = :riddleId";
        $this->deleteRoomRiddle = $this->connection->prepare($sql);
    }

    public function insertRoomQuery($difficulty, $timeLimit, $minPlayers, $maxPlayers, $image)
    {
        try {
            $this->insertRoom->execute([
                'difficulty' => $difficulty,
                'timeLimit' => $timeLimit,
                'minPlayers' => $minPlayers,
                'maxPlayers' => $maxPlayers,
                'image' => $image
            ]);

            $sql = "SELECT MAX(id) FROM escapeRoom";
            $query = $this->connection->query($sql) or die("failed!");
            $result = $query->fetch();

            return ["success" => true, "id" => $result[0]];
        } catch (PDOException $e) {
            $this->connection->rollBack();
            return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        }
    }

    public function insertRoomTranslationQuery($roomId, $language, $name)
    {
        try {
            $this->insertRoomTranslation->execute([
                'roomId' => $roomId,
                'language' => $language,
                'name' => $name,
            ]);

            return ["success" => true];
        } catch (PDOException $e) {
            $this->connection->rollBack();
            return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        }
    }

    public function insertRiddleQuery($type, $image)
    {
        try {
            $this->insertRiddle->execute([
                'type' => $type,
                'image' => $image
            ]);

            $sql = "SELECT MAX(id) FROM riddle";
            $query = $this->connection->query($sql) or die("failed!");
            $result = $query->fetch();

            return ["success" => true, "id" => $result[0]];
        } catch (PDOException $e) {
            $this->connection->rollBack();
            return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        }
    }

    public function insertRiddleTranslationQuery($riddleId, $language, $task, $solution)
    {
        try {
            $this->insertRiddleTranslation->execute([
                'riddleId' => $riddleId,
                'language' => $language,
                'task' => $task,
                'solution' => $solution
            ]);

            return ["success" => true];
        } catch (PDOException $e) {
            $this->connection->rollBack();
            return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        }
    }

    public function insertRoomRiddleQuery($roomId, $riddleId)
    {
        try {
            if (!$this->selectRoomQuery($roomId)["data"] or !$this->selectRiddleQuery($riddleId)["data"]) {
                return ["success" => false, "error" => "To insert a roomRiddle, you need to specify valid escapeRoom and riddle ids."];
            }

            $this->insertRoomRiddle->execute([
                'roomId' => $roomId,
                'riddleId' => $riddleId,
            ]);
            return ["success" => true];
        } catch (PDOException $e) {
            $this->connection->rollBack();
            return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        }
    }

    public function selectRoomQuery($id)
    {
        try {
            $this->selectRoom->execute(['id' => $id]);
            $result = $this->selectRoom->fetch();

            return ["success" => true, "data" => $result];
        } catch (PDOException $e) {
            $this->connection->rollBack();
            return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        }
    }

    public function selectRoomTranslationQuery($roomId, $language)
    {
        try {
            $this->selectRoomTranslation->execute([
                'roomId' => $roomId,
                'language' => $language
            ]);
            $result = $this->selectRoomTranslation->fetch();

            return ["success" => true, "data" => $result];
        } catch (PDOException $e) {
            $this->connection->rollBack();
            return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        }
    }

    public function selectRiddleQuery($id)
    {
        try {
            $this->selectRiddle->execute(['id' => $id]);
            $result = $this->selectRiddle->fetch();

            return ["success" => true, "data" => $result];
        } catch (PDOException $e) {
            $this->connection->rollBack();
            return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        }
    }

    public function selectRiddleTranslationQuery($riddleId, $language)
    {
        try {
            $this->selectRiddleTranslation->execute([
                'riddleId' => $riddleId,
                'language' => $language
            ]);
            $result = $this->selectRiddleTranslation->fetch();

            return ["success" => true, "data" => $result];
        } catch (PDOException $e) {
            $this->connection->rollBack();
            return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        }
    }

    public function selectRiddlesInRoomQuery($roomId)
    {
        try {
            $riddles = array();
            $riddleIds = array_column($this->selectRoomRiddlesByRoomQuery($roomId)["data"], 'riddleId');

            foreach ($riddleIds as $riddleId) {
                array_push($riddles, $this->selectRiddleQuery($riddleId)["data"]);
            }

            if ($riddles === NULL) {
                return ["success" => false, "error" => "No riddles in the room"];
            }

            return ["success" => true, "data" => $riddles];

        } catch (PDOException $e) {
            $this->connection->rollBack();
            return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        }
    }

    public function selectRoomRiddlesByRoomQuery($roomId)
    {
        try {
            $this->selectRoomRiddlesByRoom->execute(['roomId' => $roomId]);
            $result = $this->selectRoomRiddlesByRoom->fetchAll(); # fetch, fetchColumn

            return ["success" => true, "data" => $result];
        } catch (PDOException $e) {
            $this->connection->rollBack();
            return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        }
    }

    public function selectRoomRiddlesByRiddleQuery($riddleId)
    {
        try {
            $this->selectRoomRiddlesByRiddle->execute(['riddleId' => $riddleId]);
            $result = $this->selectRoomRiddlesByRiddle->fetchAll();

            return ["success" => true, "data" => $result];
        } catch (PDOException $e) {
            $this->connection->rollBack();
            return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        }
    }

    public function selectRoomsQuery()
    {
        try {
            $this->selectRooms->execute();
            return ["success" => true, "data" => $this->selectRooms->fetchAll()];
        } catch (PDOException $e) {
            $this->connection->rollBack();
            return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        }
    }

    public function selectRoomTranslationsQuery($roomId)
    {
        try {
            $this->selectRoomTranslations->execute([
                'roomId' => $roomId
            ]);
            return ["success" => true, "data" => $this->selectRoomTranslations->fetchAll()];
        } catch (PDOException $e) {
            $this->connection->rollBack();
            return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        }
    }

    public function selectRiddlesQuery()
    {
        try {
            $this->selectRiddles->execute();
            return ["success" => true, "data" => $this->selectRiddles->fetchAll()];
        } catch (PDOException $e) {
            $this->connection->rollBack();
            return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        }
    }

    public function selectRiddleTranslationsQuery($riddleId)
    {
        try {
            $this->selectRiddleTranslations->execute([
                'riddleId' => $riddleId
            ]);
            return ["success" => true, "data" => $this->selectRiddleTranslations->fetchAll()];
        } catch (PDOException $e) {
            $this->connection->rollBack();
            return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        }
    }

    public function selectRoomRiddlesQuery()
    {
        try {
            $this->selectRoomRiddles->execute();
            return ["success" => true, "data" => $this->selectRoomRiddles->fetchAll()];
        } catch (PDOException $e) {
            $this->connection->rollBack();
            return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        }
    }

    public function selectRoomsWhereQuery(
        $language = null,
        $minDifficulty = null,
        $maxDifficulty = null,
        $minTimeLimit = null,
        $maxTimeLimit = null,
        $minPlayers = null,
        $maxPlayers = null,
    ) {
        try {
            $this->selectRoomsWhere->execute([
                'language' => $language,
                'minDifficulty' => $minDifficulty,
                'maxDifficulty' => $maxDifficulty,
                'minTimeLimit' => $minTimeLimit,
                'maxTimeLimit' => $maxTimeLimit,
                'minPlayers' => $minPlayers,
                'maxPlayers' => $maxPlayers,
            ]);

            return ["success" => true, "data" => $this->selectRoomsWhere->fetchAll()];
        } catch (PDOException $e) {
            $this->connection->rollBack();
            return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        }
    }

    public function updateRoomQuery($id, $difficulty, $timeLimit, $minPlayers, $maxPlayers, $image)
    {
        try {
            $this->updateRoom->execute([
                'id' => $id,
                'difficulty' => $difficulty,
                'timeLimit' => $timeLimit,
                'minPlayers' => $minPlayers,
                'maxPlayers' => $maxPlayers,
                'image' => $image
            ]);

            return ["success" => true];
        } catch (PDOException $e) {
            $this->connection->rollBack();
            return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        }
    }

    public function updateRoomTranslationQuery($roomId, $language, $name)
    {
        try {
            $this->updateRoomTranslation->execute([
                'roomId' => $roomId,
                'language' => $language,
                'name' => $name
            ]);

            return ["success" => true];
        } catch (PDOException $e) {
            $this->connection->rollBack();
            return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        }
    }

    public function updateRiddleQuery($id, $type, $image)
    {
        try {
            $this->updateRiddle->execute([
                'id' => $id,
                'type' => $type,
                'image' => $image
            ]);

            return ["success" => true];
        } catch (PDOException $e) {
            $this->connection->rollBack();
            return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        }
    }

    public function updateRiddleTranslationQuery($riddleId, $language, $task, $solution)
    {
        try {
            $this->updateRiddleTranslation->execute([
                'riddleId' => $riddleId,
                'language' => $language,
                'task' => $task,
                'solution' => $solution
            ]);

            return ["success" => true];
        } catch (PDOException $e) {
            $this->connection->rollBack();
            return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        }
    }

    public function deleteRoomQuery($id)
    {
        try {
            $this->deleteRoom->execute([
                'id' => $id,
            ]);

            return ["success" => true];
        } catch (PDOException $e) {
            $this->connection->rollBack();
            return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        }
    }

    public function deleteRoomTranslationQuery($roomId, $language)
    {
        try {
            $this->deleteRoomTranslation->execute([
                'roomId' => $roomId,
                'language' => $language
            ]);

            return ["success" => true];
        } catch (PDOException $e) {
            $this->connection->rollBack();
            return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        }
    }

    public function deleteRiddleQuery($id)
    {
        try {
            $this->deleteRiddle->execute([
                'id' => $id,
            ]);

            return ["success" => true];
        } catch (PDOException $e) {
            $this->connection->rollBack();
            return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        }
    }

    public function deleteRiddleTranslationQuery($riddleId, $language)
    {
        try {
            $this->deleteRiddleTranslation->execute([
                'riddleId' => $riddleId,
                'language' => $language
            ]);

            return ["success" => true];
        } catch (PDOException $e) {
            $this->connection->rollBack();
            return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        }
    }

    public function deleteRoomRiddleQuery($roomId, $riddleId)
    {
        try {
            $this->deleteRoomRiddle->execute([
                'roomId' => $roomId,
                'riddleId' => $riddleId
            ]);

            return ["success" => true];
        } catch (PDOException $e) {
            $this->connection->rollBack();
            return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        }
    }

    function __destruct()
    {
        $this->connection = null;
    }
}
?>