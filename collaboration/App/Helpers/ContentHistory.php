<?php

namespace App\Helpers;

class ContentHistory
{
    private $historyFile;
    private $maxHistory = 20;
    private $data = [];

    public function __construct($storagePath)
    {
        $this->historyFile = rtrim($storagePath, "/") . "/content_history.json";

        if (!file_exists($this->historyFile)) {
            file_put_contents($this->historyFile, json_encode(["recent" => []]));
        }

        $json = json_decode(file_get_contents($this->historyFile), true);
        $this->data = $json ?: ["recent" => []];
    }

    public function getRecent()
    {
        return $this->data["recent"];
    }

    public function add($id)
    {
        // Prepend new ID
        array_unshift($this->data["recent"], $id);

        // Keep only last 20
        $this->data["recent"] = array_slice($this->data["recent"], 0, $this->maxHistory);

        file_put_contents($this->historyFile, json_encode($this->data, JSON_PRETTY_PRINT));
    }
}

