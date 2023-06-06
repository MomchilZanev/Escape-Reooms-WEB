<?php

// TODO: Add options from config file about file formatting (prettyfy/minify).

function getJson($object) {
    return json_encode($object, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
}

function exportToJson($object, $fileName) {
    header("Content-Type: application/json");
    header("Content-Disposition: attachment; filename=\"$fileName\"");
    
    echo getJson($object);
}
?>