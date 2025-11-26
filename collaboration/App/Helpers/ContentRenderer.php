<?php

namespace App\Helpers;

class ContentRenderer
{
    private $templatePath;

    public function __construct($templatePath)
    {
        
        $this->templatePath = rtrim($templatePath, "/") . "/";
    }

    public function render(array $item)
{
    $category = $item["category"] ?? "default";
    $file = $this->templatePath . $category . ".php";

    if (!file_exists($file)) {
        $file = $this->templatePath . "default.php";
    }

    // render template
    ob_start();
    include $file;
    $output = trim(ob_get_clean());

    if (empty($output)) {
        return "<div class='content-card system'>⚠️ Empty template output for category <b>{$category}</b>.</div>";
    }

    return $output;
}

}
