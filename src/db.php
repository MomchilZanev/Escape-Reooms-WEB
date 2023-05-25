<?php
    class Database {
        private $connection;
        private $insertRoom;
        private $insertRiddle;
        private $insertRoomRiddle;
        
        private $selectRooms;
        private $selectRiddles;
        private $selectRoomRiddles;

        private $selectRiddleById;
        private $selectRoomById;
        private $selectRoomRiddleByRoom;
        
        #
        #
        # initial part
        public function __construct() {
            $config = parse_ini_file('../config/config.ini', true);

            $type = $config['db']['db_type'];
            $host = $config['db']['host'];
            $name = $config['db']['db_name'];
            $port = $config['db']['port'];
            $user = $config['db']['user'];
            $password = $config['db']['password'];

            $this->init($type, $host, $name, $port, $user, $password);
        }
  
        private function init($type, $host, $name, $port, $user, $password) {
            try {
                $this->connection = new PDO("$type:host=$host;dbname=$name;port=$port", $user, $password,
                    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

                $this->prepareStatements();
            } catch(PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }

        private function prepareStatements() {

            $sql = "INSERT INTO escaperoom (name, room_lang, difficulty,
                                            timeLimit, minPlayers, maxPlayers, image) 
                                            VALUES (?, ?, ?, ?, ?, ?, ?)";
            $this->insertRoom = $this->connection->prepare($sql);

            $sql = "INSERT INTO riddle (type, task, solution, riddle_lang, image) 
                                        VALUES (?, ?, ?, ?, ?)";
            $this->insertRiddle = $this->connection->prepare($sql);

            $sql = "INSERT INTO room_riddle (room_id, riddle_id) VALUES (?, ?)";
            $this->insertRoomRiddle = $this->connection->prepare($sql);

            $sql = "SELECT * FROM escaperoom";
            $this->selectRooms = $this->connection->prepare($sql);

            $sql = "SELECT * FROM riddle";
            $this->selectRiddles = $this->connection->prepare($sql);

            $sql = "SELECT * FROM room_riddle";
            $this->selectRoomRiddles = $this->connection->prepare($sql);

            $sql = "SELECT * FROM escaperoom WHERE room_id = ?";
            $this->selectRoomById = $this->connection->prepare($sql);

            $sql = "SELECT * FROM riddle WHERE riddle_id = ?";
            $this->selectRiddleById = $this->connection->prepare($sql);

            $sql = "SELECT * FROM room_riddle WHERE room_id = ?";
            $this->selectRoomRiddleByRoom = $this->connection->prepare($sql);
            
        }

        #
        #
        # insert part
        # return true if the insertion is finished successfully
        public function insertRoomQuery($data) {
            try {
                return $this->insertRoom->execute($data);
            } catch(PDOException $e) {
                $this->connection->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        # return true if the insertion is finished successfully
        public function insertRiddleQuery($data) {
            try {
                return $this->insertRiddle->execute($data);
            } catch(PDOException $e) {
                $this->connection->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function insertRoomRiddleQuery($data) {
            try {
                if (!$this->selectRoomByIdQuery(array($data[0])) || 
                    !$this->selectRiddleByIdQuery(array($data[1]))) {
                        return false;
                }
                return $this->insertRoomRiddle->execute($data);
            } catch(PDOException $e) {
                $this->connection->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        #
        #
        # select all part
        # return an array of arrays
        public function selectRoomsQuery() {
            try {
                $this->selectRooms->execute();
                return $this->selectRooms->fetchAll();

            } catch(PDOException $e) {
                $this->connection->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        # return an array of arrays
        public function selectRiddlesQuery() {
            try {
                $this->selectRiddles->execute();
                return $this->selectRiddles->fetchAll();;

            } catch(PDOException $e) {
                $this->connection->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        # return an array of arrays
        public function selectRoomRiddlesQuery() {
            try {
                $this->selectRoomRiddles->execute();
                return $this->selectRoomRiddles->fetchAll();;

            } catch(PDOException $e) {
                $this->connection->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        #
        #
        # select by id part
        # return an array if existed. Else returns nothing
        public function selectRoomByIdQuery($id) {

            try {
                $this->selectRoomById->execute($id);
                $result = $this->selectRoomById->fetch();   # fetch, fetchColumn
                
                if ($result === NULL) {
                    return false;
                }
                return $result;

            } catch(PDOException $e) {
                $this->connection->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        public function selectRiddleByIdQuery($id) {

            try {
                $this->selectRiddleById->execute($id);
                $result = $this->selectRiddleById->fetch();    # fetch, fetchColumn
                
                if ($result === NULL) {
                    return false;
                }
                return $result;

            } catch(PDOException $e) {
                $this->connection->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        #return array of arrays of room_id | riddle_id
        public function selectRoomRiddleByRoomQuery($id) {

            try {
                $this->selectRoomRiddleByRoom->execute($id);
                $result = $this->selectRoomRiddleByRoom->fetchAll();    # fetch, fetchColumn
                
                if ($result === NULL) {
                    return false;
                }
                return $result;

            } catch(PDOException $e) {
                $this->connection->rollBack();
                return ["success" => false, "error" => "Connection failed: " . $e->getMessage()];
            }
        }

        #
        #
        # filter part
        # args are: nameColumn; compareSign: =,<,>,<=,>= ; value
        public function generateQueryForFilter($table, $cols, $comps, $vals) {
            $sql = "SELECT * FROM $table WHERE ";
            $conditions = array();
        
            for ($i = 0; $i < count($comps); $i++) {
                array_push($conditions, $cols[$i] . " " . $comps[$i] . " " . $vals[$i]);
            }

            $sql .= implode(" AND ", $conditions);
            return $sql;
        }
        
        public function selectQueryByFilter($table, $cols, $comps, $vals) {
            $sql = $this->generateQueryForFilter($table, $cols, $comps, $vals);
            $query = $this->connection->query($sql) or die("failed!");
            $result = $query->fetchAll();
            return $result;
        }
        /**
         * Close the connection to the DB
         */
        function __destruct() {
            $this->connection = null;
        }
    }

    #
    #
    # test part (lazy style)
    function testRoomInsert() {
        $db = new Database();
        $n = 'abc';
        $rl = 'en';
        $tl = 123;
        $df = 5;
        $minp = 1;
        $maxp = 6;
        $img = 'abracadabra';

        $arr = array($n, $rl, $df, $tl, $minp, $maxp, $img);
        $db->insertRoomQuery($arr);
        return $db->selectRoomsQuery();
    }

    function testRiddleInsert() {
        $db = new Database();

        $type = "numericc";
        $task = "How is called a man living in Vratsa";
        $sol = "Pustinqk";
        $rl = "gb";
        $image = "nema";

        $arr = array($type, $task, $sol, $rl, $image);
        $db->insertRiddleQuery($arr);
        return $db->selectRiddlesQuery();
    }

    function testRoomRiddleInsert() {
        $db = new Database();

        $room_id = 12;
        $riddle_id = 5;

        $arr = array($room_id, $riddle_id);
        $db->insertRoomRiddleQuery($arr);
        return $db->selectRoomRiddlesQuery();
    }

    function testSelectRoomByIdQuery($id) {
        $db = new Database();
        return $db->selectRoomByIdQuery(array($id));
    }

    function testSelectRiddleByIdQuery($id) {
        $db = new Database();
        return $db->selectRiddleByIdQuery(array($id));
    }

    function testSelectRoomRiddleByRoomQuery($id) {
        $db = new Database();
        return $db->selectRoomRiddleByRoomQuery(array($id));
    }

    function testFilterByRoomAndRiddle() {
        $db = new Database();

        $table = "escaperoom";
        $cols = array("difficulty", "timeLimit");
        $comps = array("<", "<=");
        $vals = array(6, 123);

        $query = $db->selectQueryByFilter($table, $cols, $comps, $vals);
        return $query;
    }
    
    function testFilterRoomQuery() {

    }

    function main() {
        
        # print_r(testRoomInsert());
        # print_r(testRiddleInsert());
        # print_r(testRoomRiddleInsert());
        # print_r(testFilterByRoom());
        # print_r(testSelectRiddleByIdQuery(5));
        # print_r(testSelectRoomByIdQuery(12));
        # print_r(testSelectRoomRiddleByRoomQuery(12));

    }

    main();
?>