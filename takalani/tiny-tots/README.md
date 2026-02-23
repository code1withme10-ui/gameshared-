# 🧸 Tiny Tots Creche Website

A modern, responsive website for Tiny Tots Creche located in Musina, Limpopo.

## 🌟 Features

### 🏠 **Public Pages**
- **Home**: Welcoming landing page with key highlights and CTAs
- **About Us**: Comprehensive information about the creche, vision, mission, and values
- **Admissions**: Complete application form with validation and file uploads
- **Gallery**: Interactive photo gallery with lightbox functionality
- **Contact**: Contact form, location information, and operating hours
- **Login**: Secure authentication for admin and parent portals

### 👨‍💼 **Admin Dashboard**
- Application management system
- Real-time statistics (total, pending, approved, rejected)
- Application review and status updates
- Quick action buttons for common tasks
- Responsive data tables

### 🔐 **Authentication System**
- Role-based access control (Headmaster, Parent)
- Secure session management
- JSON-based data storage
- Password protection

### 📱 **Mobile Responsive**
- Fully responsive design for all devices
- Touch-friendly navigation
- Optimized layouts for mobile viewing
- Fast loading times

### 🎨 **Modern Design**
- Tiny Tots brand colors (baby blue & sunny yellow)
- Smooth animations and transitions
- Professional typography
- Accessibility-focused design

## 🛠️ **Technical Stack**

### **Backend**
- **PHP 8.0+**: Server-side logic and authentication
- **JSON**: Lightweight data storage
- **Session Management**: Secure user sessions
- **File Uploads**: Document handling for admissions

### **Frontend**
- **HTML5**: Semantic markup structure
- **CSS3**: Modern styling with animations
- **JavaScript ES6+**: Interactive features
- **Responsive Design**: Mobile-first approach

### **Design System**
- **CSS Variables**: Consistent theming
- **Grid Layouts**: Modern CSS Grid
- **Flexbox**: Flexible component layouts
- **Animations**: Smooth user interactions

## 📁 **Project Structure**

```
tiny-tots/
├── index.php                 # Home page
├── about.php                 # About us page
├── admission.php              # Admission form
├── gallery.php               # Photo gallery
├── contact.php               # Contact page
├── login.php                 # Login page
├── logout.php                # Logout handler
├── admin/
│   └── dashboard.php         # Admin dashboard
├── includes/
│   ├── header.php            # Navigation header
│   ├── footer.php            # Site footer
│   └── functions.php         # Helper functions
├── assets/
│   ├── css/
│   │   └── styles.css      # Main stylesheet
│   └── js/
│       └── script.js        # Interactive scripts
└── data/
    ├── users.json            # User accounts
    ├── admissions.json       # Application data
    └── headmaster.json      # Admin credentials
```

## 🚀 **Getting Started**

### **Prerequisites**
- PHP 8.0 or higher
- Web server (Apache/Nginx)
- Modern web browser

### **Installation**
1. Copy files to web server directory
2. Ensure `data/` folder is writable (chmod 755)
3. Configure web server to point to project root
4. Access `index.php` in browser

### **Default Login**
- **Username**: `admin`
- **Password**: `admin123`
- **Role**: Headmaster Administrator

## 📋 **Key Features Explained**

### **Admission System**
- Complete form validation
- File upload support (PDF, JPG, PNG)
- Age/grade validation
- Automatic application numbering
- Email notifications (ready for integration)

### **Gallery System**
- Categorized photo display
- Lightbox image viewer
- Keyboard navigation
- Mobile touch support
- Image descriptions

### **Contact System**
- Subject-based inquiries
- Phone validation
- Map integration ready
- Emergency contact information

## 🔧 **Customization**

### **Branding**
Update CSS variables in `assets/css/styles.css`:
```css
:root {
  --primary-color: #87CEEB;      /* Baby Blue */
  --secondary-color: #FFD700;     /* Sunny Yellow */
  --accent-color: #FFA500;        /* Golden Yellow */
}
```

### **Contact Information**
Update details in:
- `index.php` - Home page contact section
- `contact.php` - Contact page information
- `includes/header.php` - Footer information

### **Content Management**
All content is managed through:
- JSON files in `data/` directory
- Admin dashboard for applications
- Direct file editing for static content

## 🔒 **Security Features**

- Input sanitization and validation
- SQL injection prevention (JSON storage)
- XSS protection
- Secure session handling
- File upload validation
- CSRF protection ready

## 📱 **Browser Support**

- ✅ Chrome 60+
- ✅ Firefox 55+
- ✅ Safari 12+
- ✅ Edge 79+
- ✅ Mobile Safari iOS 12+

## 🚀 **Future Enhancements**

- [ ] Parent portal integration
- [ ] Email notification system
- [ ] Database integration (MySQL)
- [ ] Online payment processing
- [ ] Document management system
- [ ] SMS notifications
- [ ] Multi-language support

## 📞 **Support**

For technical support or questions:
- **Email**: mollerv40@gmail.com
- **Phone**: 081 421 0084
- **Address**: 4 Copper Street, Musina, Limpopo, 0900

---

*Built with ❤️ for the little learners at Tiny Tots Creche*
