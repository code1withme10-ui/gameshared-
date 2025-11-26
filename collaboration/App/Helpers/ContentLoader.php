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
        return $this->fallbackItem("No joke files found.");
    }

    $recent = $this->history->getRecent();

    // Try up to 5 times
    for ($i = 0; $i < 5; $i++) {
        $file = $files[array_rand($files)];
        $items = json_decode(file_get_contents($file), true);

        if (!is_array($items) || empty($items)) {
            continue; // Retry
        }

        // Filter out the recent IDs
        $available = array_filter($items, fn($item) =>
            isset($item["id"]) && !in_array($item["id"], $recent)
        );

        if (empty($available)) {
            // If all items used recently, reset and retry
            $available = $items;
        }

        // Random successful selection
        $selected = $available[array_rand($available)];

        // Validate required fields
        if (empty($selected["id"]) || empty($selected["content"])) {
            continue; // Retry
        }

        // Save ID in history
        $this->history->add($selected["id"]);

        return $selected;
    }

    // If all retries fail — return fallback item
    return $this->fallbackItem("Unable to load content after multiple attempts.");
}

private function fallbackItem($message)
{
    return [
        "id" => "fallback-" . uniqid(),
        "content" => "⚠️ " . $message,
        "category" => "system",
        "fallback" => true
    ];
}

}

