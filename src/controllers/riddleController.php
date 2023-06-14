<?php
require_once(__DIR__ . "/../services/riddleService.php");
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
        case "getRiddleDetails":
            if ($method != "GET") {
                echo 'Invalid method or action.';
                return;
            }

            getRiddleDetails();
            break;
        case "getAllRiddles":
            if ($method != "GET") {
                echo 'Invalid method or action.';
                return;
            }

            getAllRiddles();
            break;
        case "addRiddle":
            if ($method != "POST") {
                echo 'Invalid method or action.';
                return;
            }
            if (!isset($_POST["riddleJson"])) {
                echo 'Missing riddle data.';
                return;
            }

            addRiddle();
            break;
        case "updateRiddle":
            if ($method != "POST") {
                echo 'Invalid method or action.';
                return;
            }
            if (!isset($_POST["riddleJson"])) {
                echo 'Missing riddle data.';
                return;
            }

            updateRiddle();
            break;
        case "translateRiddle":
            if ($method != "POST") {
                echo 'Invalid method or action.';
                return;
            }
            if (!isset($_POST["riddleJson"])) {
                echo 'Missing translation data.';
                return;
            }

            translateRiddle();
            break;
        case "deleteRiddle":
            if ($method != "POST") {
                echo 'Invalid method or action.';
                return;
            }

            deleteRiddle();
            break;
        case "addRoomRiddle":
            if ($method != "POST") {
                echo 'Invalid method or action.';
                return;
            }

            addRoomRiddle();
            break;
        case "deleteRoomRiddle":
            if ($method != "POST") {
                echo 'Invalid method or action.';
                return;
            }

            deleteRoomRiddle();
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

function getRiddleDetails()
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

    $riddleService = new RiddleService();
    $result = $riddleService->getRiddleDetails($id, $language);

    $serializationService = new SerializationService();
    if ($export) {
        $serializationService->exportToJson($result, "riddle-export.json");
    } else {
        header('Content-Type: application/json; charset=UTF-8');
        echo $serializationService->getJson($result);
    }
}

function getAllRiddles()
{
    $language = null;
    $export = null;
    if (isset($_GET["language"])) {
        $language = $_GET["language"];
    }
    if (isset($_GET["export"])) {
        $export = filter_var($_GET["export"], FILTER_VALIDATE_BOOLEAN);
    }

    $riddleService = new RiddleService();
    $result = $riddleService->getAllRiddles($language);

    $serializationService = new SerializationService();
    if ($export) {
        $serializationService->exportToJson($result, "all-riddles-export.json");
    } else {
        header('Content-Type: application/json; charset=UTF-8');
        echo $serializationService->getJson($result);
    }
}

function addRiddle()
{
    $riddleService = new RiddleService();
    $riddleService->addRiddleJson($_POST["riddleJson"]);
}

function updateRiddle()
{
    $riddleService = new RiddleService();
    $riddleService->updateRiddleJson($_POST["riddleJson"]);
}

function translateRiddle()
{
    $riddleService = new RiddleService();
    $riddleService->translateRiddleJson($_POST["riddleJson"]);
}

function deleteRiddle()
{
    $id = null;
    if (isset($_POST["id"])) {
        $id = $_POST["id"];
    }

    $riddleService = new RiddleService();
    $riddleService->deleteRiddle($id);
}

function addRoomRiddle()
{
    $roomId = null;
    $riddleId = null;
    if (isset($_POST["roomId"])) {
        $roomId = $_POST["roomId"];
    }
    if (isset($_POST["riddleId"])) {
        $riddleId = $_POST["riddleId"];
    }

    $riddleService = new RiddleService();
    $riddleService->addRoomRiddle($roomId, $riddleId);
}

function deleteRoomRiddle()
{
    $roomId = null;
    $riddleId = null;
    if (isset($_POST["roomId"])) {
        $roomId = $_POST["roomId"];
    }
    if (isset($_POST["riddleId"])) {
        $riddleId = $_POST["riddleId"];
    }

    $riddleService = new RiddleService();
    $riddleService->deleteRoomRiddle($roomId, $riddleId);
}

function importFromJson()
{
    $service = new RiddleService();
    $service->importFromJson($_POST["jsonContents"]);
}
?>