<?php
require_once(__DIR__ . "/../src/services/serializationService.php");
require_once(__DIR__ . "/../src/services/escapeRoomService.php");

function testExportToJson()
{
    $serviceInstance = new EscapeRoomService();
    exportToJson($serviceInstance->getAllRooms("bg"), "rooms-export.json");
}

function serializationTests()
{
    testExportToJson();
}

serializationTests();
?>