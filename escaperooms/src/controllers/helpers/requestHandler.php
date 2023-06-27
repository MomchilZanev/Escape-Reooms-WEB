<?php
function handleRequest($validEndpoints)
{
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    if (in_array($requestMethod, array_keys($validEndpoints))) {
        if (!isset($_GET['action']) and !isset($_POST['action'])) {
            echo 'No action specified';
            return;
        }

        $action = null;
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
        } else {
            $action = $_POST['action'];
        }

        if (!in_array($action, $validEndpoints[$requestMethod])) {
            echo 'Invalid request method or action: Method - ' . $requestMethod . '; Action - ' . $action;
            return;
        }

        $action();
    } else {
        echo 'Unsupported request method: ' . $requestMethod;
    }
}
?>