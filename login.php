<?php
require_once 'includes/functions.php';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $error = '';
    
    try {
        $username = sanitizeInput($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($username) || empty($password)) {
            throw new Exception("Please enter both username and password.");
        }
        
        // Check headmaster login
        $headmaster = findHeadmaster($username, $password);
        if ($headmaster) {
            $_SESSION['user'] = [
                'username' => $headmaster['username'],
                'name' => $headmaster['name'],
                'email' => $headmaster['email'],
                'role' => 'headmaster'
            ];
            header('Location: admin/dashboard.php');
            exit();
        }
        
        // Check regular user login
        $user = findUser($username, $password);
        if ($user) {
            $_SESSION['user'] = [
                'username' => $user['username'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role']
            ];
            
            // Redirect based on role
            if ($user['role'] === 'parent') {
                header('Location: parent/portal.php');
            } else {
                header('Location: index.php');
            }
            exit();
        }
        
        throw new Exception("Invalid username or password.");
        
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

require_once 'includes/header.php';
?>

<main class="home-container">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>🔐 Login</h1>
                <p>Access your Tiny Tots Creche portal</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <form id="loginForm" method="POST" class="login-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required 
                           autocomplete="username" placeholder="Enter your username">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required 
                           autocomplete="current-password" placeholder="Enter your password">
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>
            
            <div class="login-help">
                <h3>🔑 Login Information</h3>
                <div class="help-section">
                    <h4>👨‍💼 Headmaster Login:</h4>
                    <p>Username: <strong>admin</strong></p>
                    <p>Password: <strong>admin123</strong></p>
                </div>
                
                <div class="help-section">
                    <h4>👨‍👩‍👧‍👦 Parents:</h4>
                    <p>Use the credentials provided during registration</p>
                </div>
                
                <div class="help-section">
                    <h4>📞 Need Help?</h4>
                    <p>Contact us at <strong>081 421 0084</strong></p>
                    <p>Email: <strong>mollerv40@gmail.com</strong></p>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
/* Login Page Styles */
.login-container {
    max-width: 500px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

.login-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 8px 30px var(--shadow-light);
    overflow: hidden;
}

.login-header {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: var(--text-dark);
    padding: 2.5rem;
    text-align: center;
}

.login-header h1 {
    margin: 0 0 0.5rem 0;
    font-size: 2rem;
    font-weight: 600;
}

.login-header p {
    margin: 0;
    font-size: 1rem;
    opacity: 0.9;
}

.login-form {
    padding: 2.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    color: var(--text-dark);
    font-weight: 500;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.form-group input {
    width: 100%;
    padding: 1rem;
    border: 2px solid var(--light-blue);
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: var(--warm-white);
}

.form-group input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 8px rgba(135, 206, 235, 0.3);
}

.form-actions {
    margin-top: 2rem;
}

.btn {
    width: 100%;
    padding: 1rem 2rem;
    border: none;
    border-radius: 25px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(45deg, var(--secondary-color), var(--accent-color));
    color: var(--text-dark);
    box-shadow: 0 4px 15px var(--shadow-light);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px var(--shadow-medium);
}

.login-help {
    background: linear-gradient(135deg, var(--warm-white), var(--light-blue));
    padding: 2rem;
}

.login-help h3 {
    color: var(--secondary-color);
    margin: 0 0 1.5rem 0;
    font-size: 1.3rem;
    font-weight: 600;
    text-align: center;
}

.help-section {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    margin-bottom: 1rem;
    border-left: 4px solid var(--primary-color);
}

.help-section h4 {
    color: var(--primary-color);
    margin: 0 0 0.8rem 0;
    font-size: 1rem;
    font-weight: 600;
}

.help-section p {
    margin: 0.3rem 0;
    color: var(--text-dark);
    font-size: 0.9rem;
}

.alert {
    padding: 1rem;
    margin-bottom: 1.5rem;
    border-radius: 10px;
    font-weight: 500;
    text-align: center;
}

.alert-error {
    background: #ff6b6b;
    color: white;
    border-left: 5px solid #d63031;
}

@media (max-width: 768px) {
    .login-container {
        padding: 1rem 0.5rem;
    }
    
    .login-header {
        padding: 2rem 1.5rem;
    }
    
    .login-form {
        padding: 2rem 1.5rem;
    }
    
    .login-help {
        padding: 1.5rem;
    }
}
</style>

<?php require_once 'includes/footer.php'; ?>
