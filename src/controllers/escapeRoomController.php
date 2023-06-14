<?php
require_once(__DIR__ . "/../services/escapeRoomService.php");
require_once(__DIR__ . "/../services/serializationService.php");

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        if (!isset($_GET["action"])) {
            echo 'Action not specified.';
            return;
        }
        serveRequest("GET", $_GET["action"]);
        break;
    case "POST":
        if (!isset($_POST["action"])) {
            echo 'Action not specified.';
            return;
        }
        serveRequest("POST", $_POST["action"]);
        break;
    default:
        echo 'Unsupported request method.';
}

function serveRequest($method, $action)
{
    switch ($action) {
        case "getRoomDetails":
            if ($method != "GET") {
                echo 'Invalid method or action.';
                return;
            }

            getRoomDetails();
            break;
        case "getAllRooms":
            if ($method != "GET") {
                echo 'Invalid method or action.';
                return;
            }

            getAllRooms();
            break;
        case "filterRooms":
            if ($method != "GET") {
                echo 'Invalid method or action.';
                return;
            }

            filterRooms();
            break;
        case "addRoom":
            if ($method != "POST") {
                echo 'Invalid method or action.';
                return;
            }
            if (!isset($_POST["roomJson"])) {
                echo 'Missing room data.';
                return;
            }

            addRoom();
            break;
        case "updateRoom":
            if ($method != "POST") {
                echo 'Invalid method or action.';
                return;
            }
            if (!isset($_POST["roomJson"])) {
                echo 'Missing room data.';
                return;
            }

            updateRoom();
            break;
        case "translateRoom":
            if ($method != "POST") {
                echo 'Invalid method or action.';
                return;
            }
            if (!isset($_POST["roomJson"])) {
                echo 'Missing translation data.';
                return;
            }

            translateRoom();
            break;
        case "deleteRoom":
            if ($method != "POST") {
                echo 'Invalid method or action.';
                return;
            }

            deleteRoom();
            break;
        case "importFromJson":
            if ($method != "POST") {
                echo 'Invalid method or action.';
                return;
            }
            if (!isset($_POST["jsonContents"])) {
                echo 'Nothing to import.';
                return;
            }

            importFromJson();
            break;
        default:
            echo 'Unknown action.';
    }
}

function getRoomDetails()
{
    $id = null;
    $language = null;
    $export = null;
    if (isset($_GET["id"])) {
        $id = $_GET["id"];
    }
    if (isset($_GET["language"])) {
        $language = $_GET["language"];
    }
    if (isset($_GET["export"])) {
        $export = filter_var($_GET["export"], FILTER_VALIDATE_BOOLEAN);
    }

    $escapeRoomService = new EscapeRoomService();
    $result = $escapeRoomService->getRoomDetails($id, $language);

    $serializationService = new SerializationService();
    if ($export) {
        $serializationService->exportToJson($result, "room-export.json");
    } else {
        header('Content-Type: application/json; charset=UTF-8');
        echo $serializationService->getJson($result);
    }
}

function getAllRooms()
{
    $language = null;
    $export = null;
    if (isset($_GET["language"])) {
        $language = $_GET["language"];
    }
    if (isset($_GET["export"])) {
        $export = filter_var($_GET["export"], FILTER_VALIDATE_BOOLEAN);
    }

    $escapeRoomService = new EscapeRoomService();
    $result = $escapeRoomService->getAllRooms($language);

    $serializationService = new SerializationService();
    if ($export) {
        $serializationService->exportToJson($result, "all-rooms-export.json");
    } else {
        header('Content-Type: application/json; charset=UTF-8');
        echo $serializationService->getJson($result);
    }
}

function filterRooms()
{
    $language = null;
    $name = null;
    $minDifficulty = null;
    $maxDifficulty = null;
    $minTimeLimit = null;
    $maxTimeLimit = null;
    $minPlayers = null;
    $maxPlayers = null;
    $export = null;
    if (isset($_GET["language"])) {
        $language = $_GET["language"];
    }
    if (isset($_GET["name"])) {
        $name = urldecode($_GET["name"]);
    }
    if (isset($_GET["minDifficulty"])) {
        $minDifficulty = $_GET["minDifficulty"];
    }
    if (isset($_GET["maxDifficulty"])) {
        $maxDifficulty = $_GET["maxDifficulty"];
    }
    if (isset($_GET["minTimeLimit"])) {
        $minTimeLimit = $_GET["minTimeLimit"];
    }
    if (isset($_GET["maxTimeLimit"])) {
        $maxTimeLimit = $_GET["maxTimeLimit"];
    }
    if (isset($_GET["minPlayers"])) {
        $minPlayers = $_GET["minPlayers"];
    }
    if (isset($_GET["maxPlayers"])) {
        $maxPlayers = $_GET["maxPlayers"];
    }
    if (isset($_GET["export"])) {
        $export = filter_var($_GET["export"], FILTER_VALIDATE_BOOLEAN);
    }

    $escapeRoomService = new EscapeRoomService();
    $result = $escapeRoomService->filterRooms($language, $name, $minDifficulty, $maxDifficulty, $minTimeLimit, $maxTimeLimit, $minPlayers, $maxPlayers);

    $serializationService = new SerializationService();
    if ($export) {
        $serializationService->exportToJson($result, "filtered-rooms-export.json");
    } else {
        header('Content-Type: application/json; charset=UTF-8');
        echo $serializationService->getJson($result);
    }
}

function addRoom()
{
    $escapeRoomService = new EscapeRoomService();
    $escapeRoomService->addRoomJson($_POST["roomJson"]);
}

function updateRoom()
{
    $escapeRoomService = new EscapeRoomService();
    $escapeRoomService->updateRoomJson($_POST["roomJson"]);
}

function translateRoom()
{
    $escapeRoomService = new EscapeRoomService();
    $escapeRoomService->translateRoomJson($_POST["roomJson"]);
}

function deleteRoom()
{
    $id = null;
    if (isset($_POST["id"])) {
        $id = $_POST["id"];
    }

    $escapeRoomService = new EscapeRoomService();
    $escapeRoomService->deleteRoom($id);
}

function importFromJson()
{
    $escapeRoomService = new EscapeRoomService();
    $escapeRoomService->importFromJson($_POST["jsonContents"]);
}
?>