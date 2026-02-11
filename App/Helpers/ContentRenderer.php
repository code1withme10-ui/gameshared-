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

    // Make $content available to template scope
    $content = $item;

    // Catch template errors
    ob_start();

    try {
        include $file;
    } catch (Throwable $e) {
        // Template crashed
        ob_end_clean();
        return $this->systemFallback("Template crashed: " . $e->getMessage());
    }

    $output = trim(ob_get_clean());

    // ABSOLUTE FINAL GUARD AGAINST EMPTY TEMPLATE
    if ($output === "" || $output === null) {
        return $this->systemFallback(
            "Template produced no output for category <b>{$category}</b>"
        );
    }

    return $output;
}

private function systemFallback($reason)
{
    return "
        <div class='content-card system' 
             style='padding:20px;background:#ffe0e0;border-left:5px solid red;margin:15px 0;border-radius:10px;'>
            <h3>⚠️ System Fallback</h3>
            <p>{$reason}</p>
            <p>This usually means the template is empty or missing required fields.</p>
        </div>
    ";
}


}
