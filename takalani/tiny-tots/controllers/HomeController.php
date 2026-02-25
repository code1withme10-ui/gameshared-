<?php
class HomeController extends BaseController {
    public function index() {
        $this->render('home/index', [
            'pageTitle' => 'Home - Tiny Tots Creche',
            'heroTitle' => 'Welcome to Tiny Tots Creche',
            'heroSubtitle' => 'Nurturing Young Minds Since 2020',
            'highlights' => [
                [
                    'icon' => '🎓',
                    'title' => 'Quality Education',
                    'description' => 'Professional teachers and comprehensive curriculum'
                ],
                [
                    'icon' => '🎨',
                    'title' => 'Creative Learning',
                    'description' => 'Arts, music, and play-based education'
                ],
                [
                    'icon' => '🏃',
                    'title' => 'Physical Development',
                    'description' => 'Sports activities and outdoor play areas'
                ],
                [
                    'icon' => '👥',
                    'title' => 'Small Classes',
                    'description' => 'Personalized attention for every child'
                ]
            ],
            'contactInfo' => [
                'address' => '4 Copper Street, Musina, Limpopo, 0900',
                'phone' => '081 421 0084',
                'email' => 'mollerv40@gmail.com',
                'hours' => 'Monday - Friday: 7:00 AM - 5:00 PM'
            ]
        ]);
    }
    
    public function about() {
        $this->render('about/index', [
            'pageTitle' => 'About Us - Tiny Tots Creche',
            'story' => 'Tiny Tots Creche was founded in 2020 with a vision to provide quality early childhood education in Musina. We believe in nurturing each child\'s unique potential through play-based learning and individual attention.',
            'vision' => 'To be the leading early childhood education center in Musina, providing a safe, nurturing environment where children can learn, grow, and thrive.',
            'mission' => 'To provide quality early childhood education that develops the whole child - intellectually, emotionally, socially, and physically.',
            'values' => [
                ['title' => 'Excellence', 'description' => 'We strive for excellence in everything we do'],
                ['title' => 'Integrity', 'description' => 'We act with honesty and transparency'],
                ['title' => 'Compassion', 'description' => 'We care deeply about every child\'s wellbeing'],
                ['title' => 'Innovation', 'description' => 'We embrace new teaching methods and technologies']
            ],
            'leadership' => [
                [
                    'name' => 'Vanessa Roets',
                    'position' => 'Principal & Founder',
                    'bio' => 'Vanessa has over 15 years of experience in early childhood education and is passionate about creating a nurturing learning environment.',
                    'image' => '/public/images/principal.jpg'
                ]
            ],
            'facilities' => [
                ['name' => 'Classrooms', 'description' => 'Bright, spacious classrooms with modern learning materials'],
                ['name' => 'Playground', 'description' => 'Safe outdoor play area with age-appropriate equipment'],
                ['name' => 'Library', 'description' => 'Well-stocked library with children\'s books and educational materials'],
                ['name' => 'Art Room', 'description' => 'Creative space for arts and crafts activities'],
                ['name' => 'Computer Lab', 'description' => 'Age-appropriate computer literacy programs'],
                ['name' => 'Kitchen', 'description' => 'Nutritious meals prepared in our hygienic kitchen']
            ]
        ]);
    }
    
    public function contact() {
        $this->render('contact/index', [
            'pageTitle' => 'Contact Us - Tiny Tots Creche',
            'contactInfo' => [
                'address' => '4 Copper Street, Musina, Limpopo, 0900',
                'phone' => '081 421 0084',
                'email' => 'mollerv40@gmail.com',
                'hours' => [
                    'Monday - Friday: 7:00 AM - 5:00 PM',
                    'Saturday: 8:00 AM - 12:00 PM',
                    'Sunday: Closed'
                ]
            ],
            'emergencyContact' => [
                'phone' => '081 421 0084',
                'alternative' => 'Emergency services: 10111'
            ]
        ]);
    }
    
    public function gallery() {
        $this->render('gallery/index', [
            'pageTitle' => 'Gallery - Tiny Tots Creche',
            'categories' => [
                'classroom' => [
                    'title' => 'Classroom Activities',
                    'images' => [
                        ['src' => '/public/images/gallery/classroom1.jpg', 'alt' => 'Children learning in classroom'],
                        ['src' => '/public/images/gallery/classroom2.jpg', 'alt' => 'Interactive learning session'],
                        ['src' => '/public/images/gallery/classroom3.jpg', 'alt' => 'Story time with teacher']
                    ]
                ],
                'creative' => [
                    'title' => 'Creative Corner',
                    'images' => [
                        ['src' => '/public/images/gallery/art1.jpg', 'alt' => 'Art and craft activities'],
                        ['src' => '/public/images/gallery/art2.jpg', 'alt' => 'Music and movement'],
                        ['src' => '/public/images/gallery/art3.jpg', 'alt' => 'Drama and role play']
                    ]
                ],
                'outdoor' => [
                    'title' => 'Outdoor Play',
                    'images' => [
                        ['src' => '/public/images/gallery/play1.jpg', 'alt' => 'Playground activities'],
                        ['src' => '/public/images/gallery/play2.jpg', 'alt' => 'Sports and games'],
                        ['src' => '/public/images/gallery/play3.jpg', 'alt' => 'Nature exploration']
                    ]
                ]
            ]
        ]);
    }
}
?>
