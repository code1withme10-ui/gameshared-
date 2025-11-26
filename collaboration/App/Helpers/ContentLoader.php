<?php

namespace App\Helpers;

class ContentLoader
{
    private $jokesPath;
    private $history;

    public function __construct($jokesPath, ContentHistory $history)
    {
        $this->jokesPath = rtrim($jokesPath, "/") . "/";
        $this->history = $history;
    }

    public function getRandomContent()
    {
        $files = glob($this->jokesPath . "*.json");

        if (empty($files)) {
            return [
                "id" => "error-000",
                "content" => "⚠️ No joke files found in data/jokes/",
                "category" => "system"
            ];
        }

        // Pick random category file
        $file = $files[array_rand($files)];
        $items = json_decode(file_get_contents($file), true);

        if (!is_array($items)) {
            return [
                "id" => "error-001",
                "content" => "⚠️ Invalid JSON format in: " . basename($file),
                "category" => "system"
            ];
        }

        // Filter out last 20 used items
        $recent = $this->history->getRecent();
        $available = array_filter($items, fn($item) =>
            !in_array($item["id"], $recent)
        );

        // If everything was shown, allow all again
        if (empty($available)) {
            $available = $items;
        }

        // Pick random entry
        $selected = $available[array_rand($available)];

        // Store ID in history
        $this->history->add($selected["id"]);

        return $selected;
    }
}

