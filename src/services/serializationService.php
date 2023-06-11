<?php

// TODO: Add options from config file about file formatting (prettyfy/minify).

class SerializationService
{
    public function getJson($object)
    {
        return json_encode($object, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function getObject($json)
    {
        return json_decode($json);
    }

    public function exportToJson($object, $fileName)
    {
        $json = $this->getJson($object);

        if (!$json) {
            return false;
        }

        header("Content-Type: application/json; charset=UTF-8");
        header("Content-Disposition: attachment; filename=\"$fileName\"");

        echo $json;
    }
}

?>