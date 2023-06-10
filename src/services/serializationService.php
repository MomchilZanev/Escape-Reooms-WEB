<?php

// TODO: Add options from config file about file formatting (prettyfy/minify).
function getJson($object)
{
    return json_encode($object, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
}

function getObject($json)
{
    return json_decode($json);
}

function exportToJson($object, $fileName)
{
    $json = getJson($object);

    if (!$json) {
        return false;
    }

    header("Content-Type: application/json");
    header("Content-Disposition: attachment; filename=\"$fileName\"");

    echo $json;
}
?>