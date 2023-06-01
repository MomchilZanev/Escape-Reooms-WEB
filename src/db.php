<?php
class Database
{
    private $connection;

    private $insertRoom;
    private $insertRiddle;
    private $insertRoomRiddle;

    private $selectRoomById;
    private $selectRiddleById;
    private $selectRoomRiddlesByRoomId;
    private $selectRoomRiddlesByRiddleId;

    private $selectRooms;
    private $selectRiddles;
    private $selectRoomRiddles;

    private $selectRoomsFilter;

    public function __construct()
    {
        $config = parse_ini_file('../config/config.ini', true);

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

        $sql = "INSERT INTO escapeRoom (name, language, difficulty, timeLimit, minPlayers, maxPlayers, image) 
                VALUES (:name, :language, :difficulty, :timeLimit, :minPlayers, :maxPlayers, :image)";
        $this->insertRoom = $this->connection->prepare($sql);

        $sql = "INSERT INTO riddle (type, language, task, solution, image) 
                VALUES (:type, :language, :task, :solution, :image)";
        $this->insertRiddle = $this->connection->prepare($sql);

        $sql = "INSERT INTO roomRiddle (roomId, riddleId)
                VALUES (:roomId, :riddleId)";
        $this->insertRoomRiddle = $this->connection->prepare($sql);

        $sql = "SELECT * FROM escapeRoom";
        $this->selectRooms = $this->connection->prepare($sql);

        $sql = "SELECT * FROM riddle";
        $this->selectRiddles = $this->connection->prepare($sql);

        $sql = "SELECT * FROM roomRiddle";
        $this->selectRoomRiddles = $this->connection->prepare($sql);

        $sql = "SELECT * FROM escapeRoom 
                WHERE id = :id";
        $this->selectRoomById = $this->connection->prepare($sql);

        $sql = "SELECT * FROM riddle
                WHERE id = :id";
        $this->selectRiddleById = $this->connection->prepare($sql);

        $sql = "SELECT * FROM roomRiddle 
                WHERE roomId = :roomId";
        $this->selectRoomRiddlesByRoomId = $this->connection->prepare($sql);

        $sql = "SELECT * FROM roomRiddle 
                WHERE riddleId = :riddleId";
        $this->selectRoomRiddlesByRiddleId = $this->connection->prepare($sql);

        $sql = "SELECT * FROM escapeRoom 
                WHERE (:language IS NULL OR language = :language)
                AND (:minDifficulty IS NULL OR difficulty >= :minDifficulty)
                AND (:maxDifficulty IS NULL OR difficulty <= :maxDifficulty)
                AND (:minTimeLimit IS NULL OR timeLimit >= :minTimeLimit)
                AND (:maxTimeLimit IS NULL OR timeLimit <= :maxTimeLimit)
                AND (:minPlayers IS NULL OR minPlayers >= :minPlayers)
                AND (:maxPlayers IS NULL OR maxPlayers <= :maxPlayers)";
        $this->selectRoomsFilter = $this->connection->prepare($sql);
    }

    public function insertRoomQuery($name, $language, $difficulty, $timeLimit, $minPlayers, $maxPlayers, $image)
    {
        try {
            $this->insertRoom->execute([
                'name' => $name,
                'language' => $language,
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
      
    public function insertRiddleQuery($type, $language, $task, $solution, $image)
    {
        try {
            $this->insertRiddle->execute([
                'type' => $type,
                'language' => $language,
                'task' => $task,
                'solution' => $solution,
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

    public function insertRoomRiddleQuery($roomId, $riddleId)
    {
        try {
            if (
                !$this->selectRoomByIdQuery($roomId)["data"] or
                !$this->selectRiddleByIdQuery($riddleId)["data"]
            ) {
                return ["success" => false, "error" => "Incorrect input"];
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

    public function selectRoomByIdQuery($id)
    {
        try {
            $this->selectRoomById->execute(['id' => $id]);
            $result = $this->selectRoomById->fetch();

            return ["success" => true, "data" => $result]; # if the room doesn't exist, return null
        } catch (PDOException $e) {
            $this->connection->rollBack();
            return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        }
    }

    public function selectRiddleByIdQuery($id)
    {
        try {
            $this->selectRiddleById->execute(['id' => $id]);
            $result = $this->selectRiddleById->fetch(); # fetch, fetchColumn

            return ["success" => true, "data" => $result]; # if the room doesn't exist, return null
        } catch (PDOException $e) {
            $this->connection->rollBack();
            return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        }
    }

    public function selectRiddlesInRoomByRoomIdQuery($roomId)
    {
        try {
            $riddles = array();
            $riddleIds = array_column($this->selectRoomRiddlesByRoomIdQuery($roomId)["data"], 'riddleId');

            foreach ($riddleIds as $riddleId) {
                array_push($riddles, $this->selectRiddleByIdQuery($riddleId)["data"]);
            } # fetch, fetchColumn

            if ($riddles === NULL) {
                return ["success" => false, "error" => "No riddles in the room"];
            }
            return ["success" => true, "data" => $riddles];

        } catch (PDOException $e) {
            $this->connection->rollBack();
            return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        }
    }

    public function selectRoomRiddlesByRoomIdQuery($roomId)
    {
        try {
            $this->selectRoomRiddlesByRoomId->execute(['roomId' => $roomId]);
            $result = $this->selectRoomRiddlesByRoomId->fetchAll(); # fetch, fetchColumn

            return ["success" => true, "data" => $result]; # if the room doesn't exist, return null
        } catch (PDOException $e) {
            $this->connection->rollBack();
            return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        }
    }

    public function selectRoomRiddlesByRiddleIdQuery($riddleId)
    {
        try {
            $this->selectRoomRiddlesByRoomId->execute(['riddleId' => $riddleId]);
            $result = $this->selectRoomRiddlesByRoomId->fetchAll(); # fetch, fetchColumn

            return ["success" => true, "data" => $result]; # if the room doesn't exist, return null
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

    public function selectRoomsFilterQuery(
        $language = null,
        $minDifficulty = null,
        $maxDifficulty = null,
        $minTimeLimit = null,
        $maxTimeLimit = null,
        $minPlayers = null,
        $maxPlayers = null,
    ) {
        try {
            $this->selectRoomsFilter->execute([
                'language' => $language,
                'minDifficulty' => $minDifficulty,
                'maxDifficulty' => $maxDifficulty,
                'minTimeLimit' => $minTimeLimit,
                'maxTimeLimit' => $maxTimeLimit,
                'minPlayers' => $minPlayers,
                'maxPlayers' => $maxPlayers,
            ]);

            return ["success" => true, "data" => $this->selectRoomsFilter->fetchAll()];
        } catch (PDOException $e) {
            $this->connection->rollBack();
            return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
        }
    }

    # args are: nameColumn; compareSign: =,<,>,<=,>= ; value

    // public function selectQueryByFilter($table, $cols, $comps, $vals) {
    //     try {
    //         $sql = $this->generateQueryForFilter($table, $cols, $comps, $vals);
    //         $query = $this->connection->query($sql) or die("failed!");
    //         $result = $query->fetchAll();

    //         return ["success" => true, "data" => $result]; # $result == empty array if no results;

    //     } catch(PDOException $e) {
    //         $this->connection->rollBack();
    //         return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
    //     }
    // }


    // private function generateQueryForFilter($table, $cols, $comps, $vals) {
    //     $sql = "SELECT * FROM $table WHERE ";
    //     $conditions = array();

    //     for ($i = 0; $i < count($comps); $i++) {
    //         array_push($conditions, $cols[$i] . " " . $comps[$i] . " " . $vals[$i]);
    //     }

    //     $sql .= implode(" AND ", $conditions);
    //     return $sql;
    // }

    

    // function testFilterRooms()
    // {
    //     $db = new Database();

    //     $table = "escapeRoom"; # same for "riddle"
    //     $cols = array("difficulty", "timeLimit");
    //     $comps = array("<", "<=");
    //     $vals = array(6, 122);

    //     //$query = $db->selectQueryByFilter($table, $cols, $comps, $vals);
    //     //return $query;
    // }

    function __destruct()
    {
        $this->connection = null;
    }
}

function testRoomInsert()
{
    $db = new Database();
    $name = 'abc';
    $language = 'en';
    $difficulty = 6;
    $timeLimit = 223;
    $minPlayers = 3;
    $maxPlayers = 8;
    $image = 'nonono';

    $data = [
        'name' => $name,
        'language' => $language,
        'difficulty' => $difficulty,
        'timeLimit' => $timeLimit,
        'minPlayers' => $minPlayers,
        'maxPlayers' => $maxPlayers,
        'image' => $image
    ];

    return $db->insertRoomQuery($name, $language, $difficulty, $timeLimit, $minPlayers, $maxPlayers, $image);
}

function testRiddleInsert()
{
    $db = new Database();

    $type = "numericc";
    $language = "gb";
    $task = "How is called a man living in Vratsa";
    $solution = "Pustinqk";
    $image = "nema";

    return $db->insertRiddleQuery($type, $language, $task, $solution, $image);
}

function testRoomRiddleInsert()
{
    $db = new Database();

    $roomId = 15;
    $riddleId = 6;

    return $db->insertRoomRiddleQuery($roomId, $riddleId);
}

function testSelectRoomByIdQuery($id)
{
    $db = new Database();
    return $db->selectRoomByIdQuery($id);
}

function testSelectRiddleByIdQuery($id)
{
    $db = new Database();
    return $db->selectRiddleByIdQuery($id);
}

function testSelectRiddlesInRoomByRoomIdQuery($id)
{
    $db = new Database();
    return $db->selectRiddlesInRoomByRoomIdQuery($id);
}

function testSelectRoomsFilterQuery()
{
    $db = new Database();

    return $db->selectRoomsFilterQuery("en", 5, 6, 123, 223, 1, 8);
}

function dbMain()
{
    # print_r(testRoomInsert());
    # print_r(testRiddleInsert());
    # print_r(testRoomRiddleInsert());

    # print_r(testSelectRoomByIdQuery(17));
    # print_r(testSelectRiddleByIdQuery(5));
    # print_r(testSelectRiddlesInRoomByRoomIdQuery(16));

    # print_r(testSelectRoomsFilterQuery());
}

dbMain();
?>