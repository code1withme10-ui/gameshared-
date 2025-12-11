<?php
class HeadmasterController
{
    public $children = [];
    public $parents = [];

    public function handle()
    {
        session_start();
        require_once __DIR__ . '/../../includes/functions.php';
        require_once __DIR__ . '/../../includes/auth.php';

        requireHeadmasterLogin();

        // Load data
        $this->children = readJSON(__DIR__ . '/../../data/children.json');
        $this->parents = readJSON(__DIR__ . '/../../data/parents.json');

        // Handle approve/decline actions
        if (isset($_GET['approve']) && isset($this->children[$_GET['approve']])) {
            $this->children[$_GET['approve']]['status'] = "Approved";
            writeJSON(__DIR__ . '/../../data/children.json', $this->children);
        }

        if (isset($_GET['decline']) && isset($this->children[$_GET['decline']])) {
            $this->children[$_GET['decline']]['status'] = "Declined";
            writeJSON(__DIR__ . '/../../data/children.json', $this->children);
        }
    }

    public function getParentInfo($username)
    {
        foreach ($this->parents as $p) {
            if (isset($p['username']) && $p['username'] === $username) {
                return $p;
            }
        }
        return null;
    }
}


