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

        // Fallback template
        if (!file_exists($file)) {
            $file = $this->templatePath . "default.php";
        }

        // Make content available to template
        $content = $item;

        ob_start();
        include $file;
        return ob_get_clean();
    }
}
