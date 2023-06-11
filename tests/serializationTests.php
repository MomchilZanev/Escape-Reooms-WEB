<?php
require_once(__DIR__ . "/../src/services/escapeRoomService.php");
require_once(__DIR__ . "/../src/services/serializationService.php");

function testExportToJson()
{
    $escapeRoomServiceInstance = new EscapeRoomService();
    $serializationServiceInstance = new SerializationService();
    $serializationServiceInstance->exportToJson($escapeRoomServiceInstance->getAllRooms("bg"), "rooms-export.json");
}

function serializationTests()
{
    testExportToJson();
}

serializationTests();
?>