<?php
require_once(__DIR__ . '/../src/data/db.php');

function testInsertRoomQuery()
{
    $db = new Database();
    $name = 'Test Room';
    $language = 'en';
    $difficulty = 6;
    $timeLimit = 150;
    $minPlayers = 3;
    $maxPlayers = 8;
    $image = 'none';

    $result = $db->insertRoomQuery($difficulty, $timeLimit, $minPlayers, $maxPlayers, $image);
    if (!$result['success']) {
        print_r('Could Not add room.');
        return;
    }

    $roomId = $result['id'] . '<br/>';
    $db->insertRoomTranslationQuery($roomId, $language, $name);
}

function testInsertRiddleQuery()
{
    $db = new Database();

    $type = 'numeric';
    $language = 'bg';
    $task = 'Тестова загадка';
    $solution = 'Няма?';
    $image = 'not found';

    $result = $db->insertRiddleQuery($type, $image);
    if (!$result['success']) {
        print_r('Could Not add riddle.');
        return;
    }

    $id = $result['id'] . '<br/>';
    $db->insertRiddleTranslationQuery($id, $language, $task, $solution);
}

function testInsertRoomRiddleQuery()
{
    $db = new Database();

    $roomId = 2;
    $riddleId = 3;
    
    $db->insertRoomRiddleQuery($roomId, $riddleId);
}

function testSelectRoomQuery()
{
    $db = new Database();
    $id = 1;

    print_r($db->selectRoomQuery($id));
    print_r('<br/>');
    print_r($db->selectRoomTranslationQuery($id, 'en'));
    print_r('<br/>');
    print_r($db->selectRoomTranslationsQuery($id));
    print_r('<br/>');
}

function testSelectRiddleQuery()
{
    $db = new Database();
    $id = 1;

    print_r($db->selectRiddleQuery($id));
    print_r('<br/>');
    print_r($db->selectRiddleTranslationQuery($id, 'bg'));
    print_r('<br/>');
    print_r($db->selectRiddleTranslationsQuery($id));
    print_r('<br/>');
}

function testSelectRiddlesInRoomQuery()
{
    $db = new Database();
    $id = 1;

    print_r($db->selectRiddlesInRoomQuery($id));
}

function testSelectRoomsWhereQuery()
{
    $db = new Database();

    print_r($db->selectRoomsWhereQuery('en', 1, 10, 10, 90, 1, 10));
}

function testUpdateRoomQuery()
{
    $db = new Database();
    $id = 2;
    $name = 'Test Room Modified';
    $language = 'en';
    $difficulty = 7;
    $timeLimit = 151;
    $minPlayers = 4;
    $maxPlayers = 9;
    $image = 'no-image';

    $result = $db->updateRoomQuery($id, $difficulty, $timeLimit, $minPlayers, $maxPlayers, $image);
    if (!$result['success']) {
        print_r('Could Not update room.');
        return;
    }

    $db->updateRoomTranslationQuery($id, $language, $name);
}

function testUpdateRiddleQuery()
{
    $db = new Database();
    $id = 3;
    $type = 'rebus';
    $language = 'bg';
    $task = 'Задача?';
    $solution = 'Отговор!';
    $image = 'no-image';

    $result = $db->updateRiddleQuery($id, $type, $image);
    if (!$result['success']) {
        print_r('Could Not update riddle.');
        return;
    }

    $db->updateRiddleTranslationQuery($id, $language, $task, $solution);
}

function testDeleteRoomTranslationQuery()
{
    $db = new Database();
    $roomId = 2;
    $language = 'en';

    return $db->deleteRoomTranslationQuery($roomId, $language);
}

function testDeleteRoomQuery()
{
    $db = new Database();
    $id = 3;

    return $db->deleteRoomQuery($id);
}

function testDeleteRiddleTranslationQuery()
{
    $db = new Database();
    $riddleId = 2;
    $language = 'en';

    return $db->deleteRiddleTranslationQuery($riddleId, $language);
}

function testDeleteRiddleQuery()
{
    $db = new Database();
    $id = 3;

    return $db->deleteRiddleQuery($id);
}

function testDeleteRoomRiddleQuery()
{
    $db = new Database();
    $roomId = 1;
    $riddleId = 2;

    return $db->deleteRoomRiddleQuery($roomId, $riddleId);
}

function dbTests()
{
    testInsertRoomQuery();
    // testInsertRiddleQuery();
    // testInsertRoomRiddleQuery();
    // testSelectRoomQuery();
    // testSelectRiddleQuery();
    // testSelectRiddlesInRoomQuery();
    // testSelectRoomsWhereQuery();
    // testUpdateRoomQuery();
    // testUpdateRiddleQuery();
    // testDeleteRoomTranslationQuery();
    // testDeleteRoomQuery();
    // testDeleteRiddleTranslationQuery();
    // testDeleteRiddleQuery();
    // testDeleteRoomRiddleQuery();
}

dbTests();
?>