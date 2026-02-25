<?php
class UserModel extends BaseModel {
    public function __construct() {
        parent::__construct(USERS_FILE);
    }
    
    public function authenticate($username, $password) {
        $users = $this->readJsonFile();
        
        foreach ($users as $user) {
            if ($user['username'] === $username && $user['password'] === $password) {
                return $user;
            }
        }
        
        return false;
    }
    
    public function getUserByUsername($username) {
        $users = $this->readJsonFile();
        
        foreach ($users as $user) {
            if ($user['username'] === $username) {
                return $user;
            }
        }
        
        return false;
    }
    
    public function createUser($userData) {
        $requiredFields = ['username', 'password', 'name', 'email', 'role'];
        $missing = $this->validateRequired($userData, $requiredFields);
        
        if (!empty($missing)) {
            throw new Exception("Missing required fields: " . implode(', ', $missing));
        }
        
        if (!$this->validateEmail($userData['email'])) {
            throw new Exception("Invalid email address");
        }
        
        if ($this->getUserByUsername($userData['username'])) {
            throw new Exception("Username already exists");
        }
        
        $users = $this->readJsonFile();
        
        $newUser = [
            'id' => $this->generateId(),
            'username' => $userData['username'],
            'password' => $userData['password'], // In production, hash this!
            'name' => $userData['name'],
            'email' => $userData['email'],
            'role' => $userData['role'],
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $users[] = $newUser;
        $this->writeJsonFile($users);
        
        return $newUser;
    }
    
    public function getAllUsers() {
        return $this->readJsonFile();
    }
    
    public function updateUser($id, $userData) {
        $users = $this->readJsonFile();
        $updated = false;
        
        foreach ($users as &$user) {
            if ($user['id'] === $id) {
                $user = array_merge($user, $userData);
                $user['updated_at'] = date('Y-m-d H:i:s');
                $updated = true;
                break;
            }
        }
        
        if ($updated) {
            $this->writeJsonFile($users);
            return true;
        }
        
        return false;
    }
    
    public function deleteUser($id) {
        $users = $this->readJsonFile();
        $filteredUsers = array_filter($users, function($user) use ($id) {
            return $user['id'] !== $id;
        });
        
        if (count($filteredUsers) < count($users)) {
            $this->writeJsonFile(array_values($filteredUsers));
            return true;
        }
        
        return false;
    }
}
?>
