<?php
require_once(__DIR__ . '/./helpers/requestHandler.php');
require_once(__DIR__ . '/../services/serializationService.php');
require_once(__DIR__ . '/../services/escapeRoomService.php');

$validEndpoints = array(
    'GET' => ['getRoomDetails', 'getAllRooms', 'filterRooms'],
    'POST' => ['addRoom', 'updateRoom', 'translateRoom', 'deleteRoom', 'importFromJson']
);

handleRequest($validEndpoints);

function getRoomDetails()
{
    $id = null;
    $language = null;
    $export = null;
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        echo 'Missing room id';
        return;
    }
    if (isset($_GET['language'])) {
        $language = $_GET['language'];
    }
    if (isset($_GET['export'])) {
        $export = filter_var($_GET['export'], FILTER_VALIDATE_BOOLEAN);
    }

    $escapeRoomService = new EscapeRoomService();
    $result = $escapeRoomService->getRoomDetails($id, $language);

    $serializationService = new SerializationService();
    if ($export) {
        $serializationService->exportToJson($result, 'room-export.json');
    } else {
        header('Content-Type: application/json; charset=UTF-8');
        echo $serializationService->getJson($result);
    }
}

function getAllRooms()
{
    $language = null;
    $export = null;
    if (isset($_GET['language'])) {
        $language = $_GET['language'];
    }
    if (isset($_GET['export'])) {
        $export = filter_var($_GET['export'], FILTER_VALIDATE_BOOLEAN);
    }

    $escapeRoomService = new EscapeRoomService();
    $result = $escapeRoomService->getAllRooms($language);

    $serializationService = new SerializationService();
    if ($export) {
        $serializationService->exportToJson($result, 'all-rooms-export.json');
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
    if (isset($_GET['language'])) {
        $language = $_GET['language'];
    }
    if (isset($_GET['name'])) {
        $name = urldecode($_GET['name']);
    }
    if (isset($_GET['minDifficulty'])) {
        $minDifficulty = $_GET['minDifficulty'];
    }
    if (isset($_GET['maxDifficulty'])) {
        $maxDifficulty = $_GET['maxDifficulty'];
    }
    if (isset($_GET['minTimeLimit'])) {
        $minTimeLimit = $_GET['minTimeLimit'];
    }
    if (isset($_GET['maxTimeLimit'])) {
        $maxTimeLimit = $_GET['maxTimeLimit'];
    }
    if (isset($_GET['minPlayers'])) {
        $minPlayers = $_GET['minPlayers'];
    }
    if (isset($_GET['maxPlayers'])) {
        $maxPlayers = $_GET['maxPlayers'];
    }
    if (isset($_GET['export'])) {
        $export = filter_var($_GET['export'], FILTER_VALIDATE_BOOLEAN);
    }

    $escapeRoomService = new EscapeRoomService();
    $result = $escapeRoomService->filterRooms($language, $name, $minDifficulty, $maxDifficulty, $minTimeLimit, $maxTimeLimit, $minPlayers, $maxPlayers);

    $serializationService = new SerializationService();
    if ($export) {
        $serializationService->exportToJson($result, 'filtered-rooms-export.json');
    } else {
        header('Content-Type: application/json; charset=UTF-8');
        echo $serializationService->getJson($result);
    }
}

function addRoom()
{
    if (!isset($_POST['roomJson'])) {
        echo 'Missing room data';
        return;
    }

    $escapeRoomService = new EscapeRoomService();
    $escapeRoomService->addRoomJson($_POST['roomJson']);
}

function updateRoom()
{
    if (!isset($_POST['roomJson'])) {
        echo 'Missing room data';
        return;
    }

    $escapeRoomService = new EscapeRoomService();
    $escapeRoomService->updateRoomJson($_POST['roomJson']);
}

function translateRoom()
{
    if (!isset($_POST['roomJson'])) {
        echo 'Missing room translation data';
        return;
    }

    $escapeRoomService = new EscapeRoomService();
    $escapeRoomService->translateRoomJson($_POST['roomJson']);
}

function deleteRoom()
{
    $id = null;
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
    } else {
        echo 'Missing room id';
        return;
    }

    $escapeRoomService = new EscapeRoomService();
    $escapeRoomService->deleteRoom($id);
}

function importFromJson()
{
    if (!isset($_POST['jsonContents'])) {
        echo 'Missing JSON contents';
        return;
    }

    $escapeRoomService = new EscapeRoomService();
    $escapeRoomService->importFromJson($_POST['jsonContents']);
}
?>