# 🧸 Tiny Tots Creche - MVC Architecture

A modern, professional PHP application built with Model-View-Controller (MVC) pattern for Tiny Tots Creche.

## 🏗️ Architecture Overview

This application demonstrates proper MVC separation with:

- **Models**: Data management and business logic
- **Views**: Presentation layer with responsive design
- **Controllers**: Request handling and application flow
- **Routes**: Clean URL routing system

## 📁 Project Structure

```
tiny-tots-mvc/
├── index.php                 # Front controller and router
├── config/
│   └── config.php           # Configuration and autoloader
├── models/                  # Data layer
│   ├── BaseModel.php        # Base model with common functionality
│   ├── UserModel.php        # User management
│   └── AdmissionModel.php   # Admission management
├── controllers/             # Logic layer
│   ├── BaseController.php   # Base controller with common methods
│   ├── HomeController.php   # Public pages
│   ├── AuthController.php   # Authentication
│   ├── AdmissionController.php # Admission forms
│   └── AdminController.php  # Admin dashboard
├── views/                   # Presentation layer
│   ├── layouts/             # Common layouts
│   │   ├── header.php       # HTML header and navigation
│   │   └── footer.php       # HTML footer
│   ├── home/                # Home page views
│   ├── auth/                # Authentication views
│   ├── admission/           # Admission views
│   └── admin/               # Admin panel views
├── public/                  # Static assets
│   ├── css/
│   │   └── styles.css      # Main stylesheet
│   └── js/
│       └── script.js       # Interactive JavaScript
└── data/                   # JSON data storage
    ├── users.json           # User accounts
    ├── admissions.json      # Application data
    └── settings.json        # Application settings
```

## 🚀 Features

### **MVC Architecture**
- Clean separation of concerns
- Modular, maintainable code
- Easy to extend and test
- Professional development practices

### **Authentication System**
- Secure login/logout
- Role-based access control
- Session management
- CSRF protection

### **Admission Management**
- Complete application forms
- File upload support
- Validation and error handling
- Admin review system

### **Admin Dashboard**
- Real-time statistics
- User management
- Application review
- Settings management

### **Modern Frontend**
- Responsive design
- Smooth animations
- Interactive elements
- Mobile-friendly

## 🔧 Installation

### **Prerequisites**
- PHP 8.0 or higher
- Web server (Apache/Nginx)
- Modern web browser

### **Setup**
1. Copy files to web server directory
2. Ensure `data/` folder is writable (chmod 755)
3. Configure web server to point to project root
4. Access `index.php` in browser

### **Default Login**
- **Username**: `admin`
- **Password**: `admin123`
- **Role**: Headmaster Administrator

## 🎯 MVC Benefits Demonstrated

### **1. Separation of Concerns**
```php
// Model: Data logic
class UserModel {
    public function authenticate($username, $password) {
        // Database operations only
    }
}

// Controller: Business logic
class AuthController {
    public function login() {
        $user = $this->userModel->authenticate($username, $password);
        if ($user) {
            // Session management
        }
    }
}

// View: Presentation only
// views/auth/login.php - HTML and CSS only
```

### **2. Reusability**
- Models can be used by multiple controllers
- Views can be reused with different data
- Controllers share common base functionality

### **3. Testability**
- Each component can be tested independently
- Mock dependencies easily
- Isolated functionality

### **4. Maintainability**
- Clear code organization
- Easy to locate and fix issues
- Consistent patterns throughout

## 🔄 Request Flow

```
User Request → index.php (Router)
                ↓
            Controller
                ↓
            Model (Data)
                ↓
            View (Response)
                ↓
            User Response
```

**Example:**
1. User visits `/login`
2. Router calls `AuthController::login()`
3. Controller processes form submission
4. Controller calls `UserModel::authenticate()`
5. Model returns user data
6. Controller renders `views/auth/login.php`
7. View displays HTML to user

## 🛠️ Advanced Features

### **Routing System**
```php
$routes = [
    '/login' => ['AuthController', 'login'],
    '/admin/dashboard' => ['AdminController', 'dashboard'],
    // ... more routes
];
```

### **Base Classes**
- `BaseModel`: Common database operations
- `BaseController`: Common controller methods
- Shared functionality across all models/controllers

### **Security Features**
- Input sanitization
- CSRF protection
- Session management
- Role-based access control

### **Modern Frontend**
- Responsive CSS Grid/Flexbox
- Smooth animations
- Interactive JavaScript
- Form validation

## 📱 Responsive Design

- **Mobile-first approach**
- **Touch-friendly navigation**
- **Optimized layouts**
- **Cross-browser compatible**

## 🎨 Design System

### **CSS Variables**
```css
:root {
  --primary-color: #87CEEB;      /* Baby Blue */
  --secondary-color: #FFD700;     /* Sunny Yellow */
  --accent-color: #FFA500;        /* Golden Yellow */
}
```

### **Component-Based CSS**
- Reusable components
- Consistent styling
- Easy maintenance

## 🔄 Extending the Application

### **Adding New Features**

1. **Create Model:**
```php
class NewModel extends BaseModel {
    public function create($data) {
        // Data logic
    }
}
```

2. **Create Controller:**
```php
class NewController extends BaseController {
    public function index() {
        $this->render('new/index', ['data' => $data]);
    }
}
```

3. **Create View:**
```php
// views/new/index.php
<div class="content-wrapper">
    <h1><?= $pageTitle ?></h1>
    <!-- HTML content -->
</div>
```

4. **Add Route:**
```php
'/new' => ['NewController', 'index'],
```

### **Database Integration**
Replace JSON storage with database by updating BaseModel:

```php
class BaseModel {
    protected $db;
    
    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;dbname=tinytots', 'user', 'pass');
    }
}
```

## 🧪 Testing

### **Unit Testing Models**
```php
class UserModelTest extends PHPUnit\Framework\TestCase {
    public function testAuthentication() {
        $model = new UserModel();
        $user = $model->authenticate('admin', 'admin123');
        $this->assertNotNull($user);
    }
}
```

### **Integration Testing**
```php
class AuthControllerTest extends PHPUnit\Framework\TestCase {
    public function testLogin() {
        $_POST['username'] = 'admin';
        $_POST['password'] = 'admin123';
        
        $controller = new AuthController();
        $controller->login();
        
        $this->assertNotNull($_SESSION['user']);
    }
}
```

## 📚 Learning Resources

### **MVC Pattern**
- Separation of concerns
- Single responsibility principle
- Dependency injection
- Design patterns

### **PHP Best Practices**
- PSR standards
- Autoloading
- Security practices
- Error handling

### **Modern Frontend**
- CSS Grid and Flexbox
- JavaScript ES6+
- Responsive design
- Accessibility

## 🚀 Production Deployment

### **Web Server Configuration**

**Apache (.htaccess):**
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```

**Nginx:**
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

### **Security Considerations**
- HTTPS enforcement
- Input validation
- SQL injection prevention
- XSS protection
- File upload security

## 🎯 Next Steps

### **Enhancements**
- [ ] Database integration (MySQL/PostgreSQL)
- [ ] Email notification system
- [ ] API endpoints for mobile app
- [ ] Advanced reporting
- [ ] Multi-language support
- [ ] Payment processing

### **Performance**
- [ ] Caching implementation
- [ ] Database optimization
- [ ] CDN integration
- [ ] Image optimization

---

**This MVC application demonstrates professional PHP development practices while maintaining the Tiny Tots Creche brand and functionality. Perfect for learning modern web development!** 🧸✨
