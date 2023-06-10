<?php
require_once(__DIR__ . "/../services/escapeRoomService.php");

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
        case "importFromJson":
            if ($method != "POST") {
                echo 'Invalid method or action.';
                return;
            }
            if (!isset($_POST["jsonContents"])) {
                echo 'Nothing to import.';
                return;
            }

            $service = new EscapeRoomService();
            $service->importFromJson($_POST["jsonContents"]);
            break;
        default:
            echo 'Unknown action.';
    }
}
?>