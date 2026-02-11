<?php

class JsonStorage
{
    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;

        // If file doesn't exist, create an empty JSON array
        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([], JSON_PRETTY_PRINT));
        }
    }

    /**
     * Read JSON file and return as array
     */
    public function read(): array
    {
        $data = file_get_contents($this->filePath);
        $decoded = json_decode($data, true);
        return $decoded ?? [];
    }

    /**
     * Write array to JSON file
     */
    public function write(array $data): bool
    {
        // Use file locking to prevent race conditions
        $fp = fopen($this->filePath, 'c+');
        if (!$fp) {
            throw new Exception("Unable to open JSON file: {$this->filePath}");
        }

        if (flock($fp, LOCK_EX)) { // exclusive lock
            ftruncate($fp, 0);      // clear file
            $written = fwrite($fp, json_encode($data, JSON_PRETTY_PRINT));
            fflush($fp);
            flock($fp, LOCK_UN);    // release lock
            fclose($fp);
            return $written !== false;
        } else {
            fclose($fp);
            throw new Exception("Unable to lock JSON file: {$this->filePath}");
        }
    }
}
