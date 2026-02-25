<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Tiny Tots Creche') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/public/css/styles.css" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <h1><i class="fas fa-baby"></i> <?= htmlspecialchars($siteName ?? 'Tiny Tots Creche') ?></h1>
                </div>
                
                <!-- Mobile menu button -->
                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <i class="fas fa-bars"></i>
                </button>
                
                <!-- Navigation -->
                <nav class="main-nav" id="mainNav">
                    <ul class="nav-list">
                        <li><a href="/" class="nav-link <?= $request === '/' ? 'active' : '' ?>"><i class="fas fa-home"></i> Home</a></li>
                        <li><a href="/about" class="nav-link <?= $request === '/about' ? 'active' : '' ?>"><i class="fas fa-info-circle"></i> About</a></li>
                        <li><a href="/gallery" class="nav-link <?= $request === '/gallery' ? 'active' : '' ?>"><i class="fas fa-images"></i> Gallery</a></li>
                        <li><a href="/contact" class="nav-link <?= $request === '/contact' ? 'active' : '' ?>"><i class="fas fa-envelope"></i> Contact</a></li>
                        <li><a href="/admission" class="nav-link <?= $request === '/admission' ? 'active' : '' ?>"><i class="fas fa-graduation-cap"></i> Admission</a></li>
                        
                        <?php if (isset($user)): ?>
                            <li class="nav-dropdown">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-user"></i> <?= htmlspecialchars($user['name']) ?> <i class="fas fa-chevron-down"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="/profile"><i class="fas fa-user-edit"></i> Profile</a></li>
                                    
                                    <?php if ($user['role'] === 'headmaster'): ?>
                                        <li><a href="/admin/dashboard"><i class="fas fa-tachometer-alt"></i> Admin Dashboard</a></li>
                                        <li><a href="/admin/users"><i class="fas fa-users"></i> Manage Users</a></li>
                                        <li><a href="/admin/settings"><i class="fas fa-cog"></i> Settings</a></li>
                                    <?php elseif ($user['role'] === 'parent'): ?>
                                        <li><a href="/parent/portal"><i class="fas fa-child"></i> Parent Portal</a></li>
                                    <?php endif; ?>
                                    
                                    <li><a href="/logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li><a href="/login" class="nav-link"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                            <li><a href="/register" class="nav-link"><i class="fas fa-user-plus"></i> Register</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    <?php $flashMessages = $this->getFlashMessages(); ?>
    <?php if (!empty($flashMessages)): ?>
        <div class="flash-messages">
            <?php foreach ($flashMessages as $type => $message): ?>
                <div class="flash-message flash-<?= $type ?>">
                    <i class="fas fa-<?= $type === 'success' ? 'check-circle' : ($type === 'error' ? 'exclamation-circle' : 'info-circle') ?>"></i>
                    <?= htmlspecialchars($message) ?>
                    <button class="flash-close" onclick="this.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="main-content">
        <?php if (isset($pageTitle) && $pageTitle !== 'Home - Tiny Tots Creche'): ?>
            <div class="page-header">
                <div class="container">
                    <h1><?= htmlspecialchars($pageTitle) ?></h1>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="container">
            <div class="content-wrapper">
                <!-- Page content will be rendered here -->
            </div>
        </div>
    </main>

    <script>
        // Get current request for active navigation
        const currentRequest = '<?= $request ?? '/' ?>';
    </script>
</body>
</html>
