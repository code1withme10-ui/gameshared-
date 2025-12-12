<?php
class HeadmasterController
{
    public $children = [];
    public $parents = [];

    public function handle()
    {
        // Start session if not already started
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        require_once __DIR__ . '/../../includes/functions.php';
        require_once __DIR__ . '/../../includes/auth.php';

        requireHeadmasterLogin();

        // Load data
        $this->parents = readJSON(__DIR__ . '/../../data/parents.json');
        $this->children = readJSON(__DIR__ . '/../../data/children.json');

        // Handle approve/decline actions
        if(isset($_GET['approve'])) {
            $this->approveChild((int)$_GET['approve']);
        }
        if(isset($_GET['decline'])) {
            $this->declineChild((int)$_GET['decline']);
        }
    }

    // Approve a child application
    public function approveChild(int $index)
    {
        if(isset($this->children[$index])) {
            $this->children[$index]['status'] = 'Approved';
            writeJSON(__DIR__ . '/../../data/children.json', $this->children);
        }
    }

    // Decline a child application
    public function declineChild(int $index)
    {
        if(isset($this->children[$index])) {
            $this->children[$index]['status'] = 'Declined';
            writeJSON(__DIR__ . '/../../data/children.json', $this->children);
        }
    }

    // Get parent info by username
    public function getParentInfo(string $username)
    {
        foreach($this->parents as $p) {
            if(isset($p['username']) && $p['username'] === $username) {
                return $p;
            }
        }
        return null;
    }
}



