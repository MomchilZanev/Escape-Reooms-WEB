<?php
require_once(__DIR__ . '/./helpers/requestHandler.php');
require_once(__DIR__ . '/../services/serializationService.php');
require_once(__DIR__ . '/../services/riddleService.php');

$validEndpoints = array(
    'GET' => ['getRiddleDetails', 'getAllRiddles'],
    'POST' => ['addRiddle', 'updateRiddle', 'translateRiddle', 'deleteRiddle', 'addRoomRiddle', 'deleteRoomRiddle', 'importFromJson']
);

handleRequest($validEndpoints);

function getRiddleDetails()
{
    $id = null;
    $language = null;
    $export = null;
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        echo 'Missing riddle id';
        return;
    }
    if (isset($_GET['language'])) {
        $language = $_GET['language'];
    }
    if (isset($_GET['export'])) {
        $export = filter_var($_GET['export'], FILTER_VALIDATE_BOOLEAN);
    }

    $riddleService = new RiddleService();
    $result = $riddleService->getRiddleDetails($id, $language);

    $serializationService = new SerializationService();
    if ($export) {
        $serializationService->exportToJson($result, 'riddle-export.json');
    } else {
        header('Content-Type: application/json; charset=UTF-8');
        echo $serializationService->getJson($result);
    }
}

function getAllRiddles()
{
    $language = null;
    $export = null;
    if (isset($_GET['language'])) {
        $language = $_GET['language'];
    }
    if (isset($_GET['export'])) {
        $export = filter_var($_GET['export'], FILTER_VALIDATE_BOOLEAN);
    }

    $riddleService = new RiddleService();
    $result = $riddleService->getAllRiddles($language);

    $serializationService = new SerializationService();
    if ($export) {
        $serializationService->exportToJson($result, 'all-riddles-export.json');
    } else {
        header('Content-Type: application/json; charset=UTF-8');
        echo $serializationService->getJson($result);
    }
}

function addRiddle()
{
    if (!isset($_POST['riddleJson'])) {
        echo 'Missing riddle data';
        return;
    }

    $riddleService = new RiddleService();
    $riddleService->addRiddleJson($_POST['riddleJson']);
}

function updateRiddle()
{
    if (!isset($_POST['riddleJson'])) {
        echo 'Missing riddle data';
        return;
    }

    $riddleService = new RiddleService();
    $riddleService->updateRiddleJson($_POST['riddleJson']);
}

function translateRiddle()
{
    if (!isset($_POST['riddleJson'])) {
        echo 'Missing riddle translation data';
        return;
    }

    $riddleService = new RiddleService();
    $riddleService->translateRiddleJson($_POST['riddleJson']);
}

function deleteRiddle()
{
    $id = null;
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
    } else {
        echo 'Missing riddle id';
        return;
    }

    $riddleService = new RiddleService();
    $riddleService->deleteRiddle($id);
}

function addRoomRiddle()
{
    $roomId = null;
    $riddleId = null;
    if (isset($_POST['roomId'])) {
        $roomId = $_POST['roomId'];
    } else {
        echo 'Missing room id';
        return;
    }
    if (isset($_POST['riddleId'])) {
        $riddleId = $_POST['riddleId'];
    } else {
        echo 'Missing riddle id';
        return;
    }

    $riddleService = new RiddleService();
    $riddleService->addRoomRiddle($roomId, $riddleId);
}

function deleteRoomRiddle()
{
    $roomId = null;
    $riddleId = null;
    if (isset($_POST['roomId'])) {
        $roomId = $_POST['roomId'];
    } else {
        echo 'Missing room id';
        return;
    }
    if (isset($_POST['riddleId'])) {
        $riddleId = $_POST['riddleId'];
    } else {
        echo 'Missing riddle id';
        return;
    }

    $riddleService = new RiddleService();
    $riddleService->deleteRoomRiddle($roomId, $riddleId);
}

function importFromJson()
{
    if (!isset($_POST['jsonContents'])) {
        echo 'Missing JSON contents';
        return;
    }

    $service = new RiddleService();
    $service->importFromJson($_POST['jsonContents']);
}
?>