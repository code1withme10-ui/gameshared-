<?php
// MVC Controller for Home Page
class HomeController {
    private $data = [];
    
    public function __construct() {
        // Initialize data for the view
        $this->data = [
            'pageTitle' => 'Home - Tiny Tots Creche',
            'heroTitle' => 'Tiny Tots Creche',
            'heroSubtitle' => 'Where little minds grow, explore, and shine!',
            'heroTagline' => 'From 3 months to Grade RR • Aftercare also available',
            'welcomeMessage' => 'We provide a safe, nurturing, and stimulating environment where children are encouraged to discover their talents, build confidence, and develop a love for learning.',
            'welcomeDetails' => 'Our dedicated caregivers are passionate about supporting every child\'s emotional, social, physical, and intellectual development through structured activities, creative play, and positive guidance.',
            'highlights' => [
                [
                    'title' => '🏠 Home-Away-From-Home',
                    'description' => 'Warm, family-like atmosphere where children feel loved and secure'
                ],
                [
                    'title' => '👤 Individual Attention',
                    'description' => 'We understand each child\'s unique personality and needs'
                ],
                [
                    'title' => '🤝 Parent Partnerships',
                    'description' => 'Open communication and active parent involvement'
                ],
                [
                    'title' => '🌟 Holistic Development',
                    'description' => 'Nurturing emotional intelligence, social skills, and creativity'
                ],
                [
                    'title' => '🛡️ Safe Environment',
                    'description' => 'Structured learning with guided play and safety first'
                ],
                [
                    'title' => '📚 School Ready',
                    'description' => 'Preparing children for Grade R and formal schooling'
                ]
            ],
            'ctaTitle' => 'Ready to Join Our Family?',
            'ctaMessage' => 'Give your child the foundation they need for a bright and successful future.',
            'contactInfo' => [
                'address' => '4 Copper Street, Musina, Limpopo, 0900',
                'phone' => '081 421 0084',
                'email' => 'mollerv40@gmail.com',
                'emis' => '973304431',
                'hours' => 'Monday to Friday: 7:00 AM - 5:30 PM'
            ]
        ];
    }
    
    public function index() {
        // Extract data for use in view
        extract($this->data);
        
        // Include header
        require_once 'includes/header.php';
        
        // Include the MVC home view
        require_once 'views/home/index.php';
        
        // Include footer
        require_once 'includes/footer.php';
    }
    
    public function about() {
        // Include header
        require_once 'includes/header.php';
        
        // Include the about view
        $this->renderAboutView();
        
        // Include footer
        require_once 'includes/footer.php';
    }
    
    private function renderAboutView() {
        ?>
        <main class="home-container">
            <section class="about-hero">
                <h1>About Tiny Tots Creche</h1>
                <p class="hero-subtitle">Nurturing young minds since 2014</p>
            </section>

            <section class="about-background">
                <h2>🏛️ Our Story</h2>
                <div class="content-card">
                    <p>Tiny Tots Creche in Musina began as a locally focused early childhood development centre dedicated to nurturing and educating young children in our community. Official records indicate that Tiny Tots was registered in 2014, marking its formal establishment as a licensed daycare and preschool provider in the region.</p>
                    
                    <p>Since its founding, the creche has grown into a trusted place for families in Musina, offering age-appropriate learning and play activities for children from infancy through their early years. Staff at Tiny Tots focus on fostering cognitive, social, emotional, and physical development through guided play, exploration, and interactive learning — helping prepare children for the transition into formal schooling.</p>
                    
                    <p>Over the years, Tiny Tots has earned a positive reputation among parents and caregivers for its warm environment and committed team. Though small, the centre has been regarded locally as one of the respected early childhood education options in town.</p>
                </div>
            </section>

            <section class="vision-mission">
                <div class="vm-grid">
                    <div class="vm-card vision-card">
                        <h2>👁️ Our Vision</h2>
                        <p>To be a leading early childhood development centre in Musina that provides a safe, nurturing, and stimulating environment where every child is valued, inspired to learn, and empowered to reach their full potential.</p>
                    </div>
                    
                    <div class="vm-card mission-card">
                        <h2>🎯 Our Mission</h2>
                        <p>Tiny Tots Creche is committed to providing quality early childhood development in a safe, loving, and stimulating environment. We strive to nurture each child's intellectual, emotional, social, and physical growth through structured learning, creative play, and positive guidance. In partnership with parents and the community, we aim to build a strong foundation that prepares children for lifelong learning and future success.</p>
                    </div>
                </div>
            </section>

            <section class="core-values">
                <h2>💎 Our Core Values</h2>
                <div class="values-grid">
                    <div class="value-item">
                        <div class="value-icon">🌟</div>
                        <h3>Excellence</h3>
                        <p>We strive for excellence in everything we do, from curriculum design to daily interactions with children.</p>
                    </div>
                    
                    <div class="value-item">
                        <div class="value-icon">🤝</div>
                        <h3>Integrity</h3>
                        <p>We act with honesty and transparency, building trust with parents, children, and our community.</p>
                    </div>
                    
                    <div class="value-item">
                        <div class="value-icon">❤️</div>
                        <h3>Compassion</h3>
                        <p>We care deeply about every child's wellbeing and treat each child with love, respect, and understanding.</p>
                    </div>
                    
                    <div class="value-item">
                        <div class="value-icon">🚀</div>
                        <h3>Innovation</h3>
                        <p>We embrace new teaching methods and technologies to enhance learning and development.</p>
                    </div>
                </div>
            </section>

            <section class="management-section">
                <h2>👩‍💼 Our Leadership</h2>
                <div class="management-card">
                    <div class="leader-profile">
                        <div class="leader-image">
                            <div class="image-placeholder">
                                <i class="fas fa-user-tie"></i>
                                <span>Vanessa Roets</span>
                            </div>
                        </div>
                        <div class="leader-info">
                            <h3>Vanessa Roets</h3>
                            <p class="leader-title">Principal & Founder</p>
                            <p>Vanessa is the heart and soul of Tiny Tots Creche. With over 15 years of experience in early childhood education, she brings passion, expertise, and a deep commitment to nurturing young minds. Her leadership has guided Tiny Tots from a small daycare to a respected early childhood development centre in Musina.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="facilities-section">
                <h2>🏫 Our Facilities</h2>
                <div class="facilities-grid">
                    <div class="facility-item">
                        <div class="facility-icon">🏠</div>
                        <h3>Classrooms</h3>
                        <p>Bright, spacious classrooms with modern learning materials and age-appropriate equipment.</p>
                    </div>
                    
                    <div class="facility-item">
                        <div class="facility-icon">🎨</div>
                        <h3>Art Room</h3>
                        <p>Creative space for arts and crafts activities, allowing children to express themselves through various art forms.</p>
                    </div>
                    
                    <div class="facility-item">
                        <div class="facility-icon">📚</div>
                        <h3>Library</h3>
                        <p>Well-stocked library with children's books and educational materials to foster a love for reading.</p>
                    </div>
                    
                    <div class="facility-item">
                        <div class="facility-icon">🏃</div>
                        <h3>Playground</h3>
                        <p>Safe outdoor play area with age-appropriate equipment for physical development and fun.</p>
                    </div>
                    
                    <div class="facility-item">
                        <div class="facility-icon">💻</div>
                        <h3>Computer Lab</h3>
                        <p>Age-appropriate computer literacy programs to introduce children to technology in a controlled environment.</p>
                    </div>
                    
                    <div class="facility-item">
                        <div class="facility-icon">🍽️</div>
                        <h3>Kitchen</h3>
                        <p>Hygienic kitchen facility where nutritious meals are prepared daily for the children.</p>
                    </div>
                </div>
            </section>
        </main>
        <?php
    }
    
    public function contact() {
        // Include header
        require_once 'includes/header.php';
        
        // Include the contact view
        $this->renderContactView();
        
        // Include footer
        require_once 'includes/footer.php';
    }
    
    private function renderContactView() {
        ?>
        <main class="home-container">
            <section class="contact-hero">
                <h1>Contact Us</h1>
                <p>Get in touch with Tiny Tots Creche - we're here to help!</p>
            </section>

            <div class="contact-container">
                <div class="contact-grid">
                    <div class="contact-form-section">
                        <div class="form-card">
                            <h2>Send Us a Message</h2>
                            <p>Have questions about our programs or want to schedule a visit? Fill out the form below and we'll get back to you soon!</p>
                            
                            <form id="contactForm" method="POST" action="contact.php" class="contact-form">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="name">Full Name *</label>
                                        <input type="text" id="name" name="name" required placeholder="Enter your full name">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="email">Email Address *</label>
                                        <input type="email" id="email" name="email" required placeholder="your.email@example.com">
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="phone">Phone Number *</label>
                                        <input type="tel" id="phone" name="phone" required placeholder="081 421 0084">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="subject">Subject *</label>
                                        <select id="subject" name="subject" required>
                                            <option value="">Select a subject</option>
                                            <option value="admission">Admission Inquiry</option>
                                            <option value="tour">Schedule a Visit</option>
                                            <option value="general">General Question</option>
                                            <option value="feedback">Feedback</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="message">Message *</label>
                                    <textarea id="message" name="message" rows="5" required placeholder="Tell us more about your inquiry..."></textarea>
                                </div>
                                
                                <div class="form-actions">
                                    <button type="submit" class="cta-btn">Send Message</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="contact-info-section">
                        <div class="info-card">
                            <h3>📍 Visit Us</h3>
                            <p>4 Copper Street, Musina, Limpopo, 0900</p>
                            <div class="map-placeholder">
                                <i class="fas fa-map"></i>
                                <span>Interactive Map</span>
                            </div>
                        </div>
                        
                        <div class="info-card">
                            <h3>📞 Call Us</h3>
                            <p><strong>Main:</strong> 081 421 0084</p>
                            <p><strong>Email:</strong> mollerv40@gmail.com</p>
                        </div>
                        
                        <div class="info-card">
                            <h3>🕐 Operating Hours</h3>
                            <p>Monday - Friday: 7:00 AM - 5:30 PM</p>
                            <p>Saturday: 8:00 AM - 12:00 PM</p>
                            <p>Sunday: Closed</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php
    }
    
    public function gallery() {
        // Include header
        require_once 'includes/header.php';
        
        // Include the gallery view
        $this->renderGalleryView();
        
        // Include footer
        require_once 'includes/footer.php';
    }
    
    private function renderGalleryView() {
        ?>
        <main class="home-container">
            <section class="gallery-hero">
                <h1>Gallery</h1>
                <p>Take a visual journey through life at Tiny Tots Creche</p>
            </section>

            <div class="gallery-container">
                <section class="gallery-section">
                    <h2>🏫 Classroom Activities</h2>
                    <div class="gallery-grid">
                        <div class="gallery-item">
                            <div class="image-placeholder">
                                <i class="fas fa-chalkboard-teacher"></i>
                                <span>Learning Time</span>
                            </div>
                        </div>
                        <div class="gallery-item">
                            <div class="image-placeholder">
                                <i class="fas fa-book"></i>
                                <span>Story Time</span>
                            </div>
                        </div>
                        <div class="gallery-item">
                            <div class="image-placeholder">
                                <i class="fas fa-puzzle-piece"></i>
                                <span>Puzzle Activities</span>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="gallery-section">
                    <h2>🎨 Creative Corner</h2>
                    <div class="gallery-grid">
                        <div class="gallery-item">
                            <div class="image-placeholder">
                                <i class="fas fa-palette"></i>
                                <span>Art & Craft</span>
                            </div>
                        </div>
                        <div class="gallery-item">
                            <div class="image-placeholder">
                                <i class="fas fa-music"></i>
                                <span>Music Time</span>
                            </div>
                        </div>
                        <div class="gallery-item">
                            <div class="image-placeholder">
                                <i class="fas fa-theater-masks"></i>
                                <span>Drama Play</span>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="gallery-section">
                    <h2>🏃 Outdoor Play</h2>
                    <div class="gallery-grid">
                        <div class="gallery-item">
                            <div class="image-placeholder">
                                <i class="fas fa-running"></i>
                                <span>Playground Fun</span>
                            </div>
                        </div>
                        <div class="gallery-item">
                            <div class="image-placeholder">
                                <i class="fas fa-basketball-ball"></i>
                                <span>Sports Activities</span>
                            </div>
                        </div>
                        <div class="gallery-item">
                            <div class="image-placeholder">
                                <i class="fas fa-tree"></i>
                                <span>Nature Exploration</span>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </main>
        <?php
    }
}
?>
