<?php
require_once 'includes/functions.php';
require_once 'includes/header.php';
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
                <h3>❤️ Love and Care</h3>
                <p>We provide a warm, nurturing environment where every child feels safe, valued, and supported.</p>
            </div>
            
            <div class="value-item">
                <h3>🤝 Respect</h3>
                <p>We treat children, parents, and staff with dignity, kindness, and fairness at all times.</p>
            </div>
            
            <div class="value-item">
                <h3>🛡️ Safety and Protection</h3>
                <p>The wellbeing of every child is our top priority. We maintain a secure and healthy learning environment.</p>
            </div>
            
            <div class="value-item">
                <h3>📚 Quality Early Learning</h3>
                <p>We are committed to delivering age-appropriate, stimulating activities that promote holistic development.</p>
            </div>
            
            <div class="value-item">
                <h3>⚖️ Integrity and Accountability</h3>
                <p>We act honestly, responsibly, and transparently in all our interactions and decisions.</p>
            </div>
            
            <div class="value-item">
                <h3>👨‍👩‍👧‍👦 Partnership with Parents</h3>
                <p>We believe parents are essential partners in a child's development and maintain open communication and collaboration.</p>
            </div>
            
            <div class="value-item">
                <h3>🌈 Inclusivity</h3>
                <p>We embrace diversity and ensure that every child, regardless of background or ability, has equal opportunities to learn and grow.</p>
            </div>
        </div>
    </section>

    <section class="management-section">
        <h2>👩‍🏫 Our Leadership</h2>
        <div class="management-card">
            <h3>Headteacher / Principal</h3>
            <div class="leader-info">
                <h4>Vanessa Roets</h4>
                <p>As the founder and principal of Tiny Tots Creche, Vanessa brings years of experience in early childhood development and a deep commitment to nurturing young minds. Her leadership has guided Tiny Tots from a small local creche to a respected institution in Musina.</p>
                <p><strong>Qualifications:</strong> Early Childhood Development Certification</p>
                <p><strong>Experience:</strong> 10+ years in early childhood education</p>
                <p><strong>Philosophy:</strong> "Every child is unique and full of potential — we are committed to helping them shine."</p>
            </div>
        </div>
    </section>

    <section class="facilities-section">
        <h2>🏫 Our Facilities</h2>
        <div class="facilities-grid">
            <div class="facility-item">
                <h3>📚 Classrooms</h3>
                <p>Bright, spacious classrooms designed for different age groups with age-appropriate learning materials and equipment.</p>
            </div>
            
            <div class="facility-item">
                <h3>🎨 Play Areas</h3>
                <p>Safe, supervised outdoor play areas with equipment designed to develop gross motor skills and encourage social interaction.</p>
            </div>
            
            <div class="facility-item">
                <h3>🛡️ Safety Measures</h3>
                <p>Secure fencing, child-proof facilities, first aid kits, and staff trained in emergency procedures to ensure child safety at all times.</p>
            </div>
        </div>
    </section>
</main>

<style>
/* About Page Specific Styles */
.about-hero {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, var(--light-blue), var(--primary-color));
    border-radius: 20px;
    margin-bottom: 3rem;
    box-shadow: 0 8px 30px var(--shadow-light);
}

.about-hero h1 {
    color: var(--secondary-color);
    font-size: 2.8rem;
    margin: 0 0 1rem 0;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
}

.hero-subtitle {
    color: var(--text-dark);
    font-size: 1.3rem;
    margin: 0;
    font-weight: 500;
}

.about-background {
    margin: 3rem 0;
}

.about-background h2 {
    color: var(--secondary-color);
    text-align: center;
    font-size: 2.2rem;
    margin-bottom: 2rem;
    font-weight: 600;
}

.content-card {
    background: white;
    padding: 3rem;
    border-radius: 20px;
    box-shadow: 0 8px 25px var(--shadow-light);
    line-height: 1.8;
    max-width: 800px;
    margin: 0 auto;
}

.content-card p {
    margin-bottom: 1.5rem;
    color: var(--text-dark);
    font-size: 1.1rem;
}

.vision-mission {
    margin: 4rem 0;
}

.vm-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.vm-card {
    padding: 2.5rem;
    border-radius: 20px;
    box-shadow: 0 8px 25px var(--shadow-light);
    transition: transform 0.3s ease;
}

.vm-card:hover {
    transform: translateY(-5px);
}

.vision-card {
    background: linear-gradient(135deg, var(--primary-color), var(--light-blue));
}

.mission-card {
    background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
}

.vm-card h2 {
    color: var(--text-dark);
    margin: 0 0 1.5rem 0;
    text-align: center;
    font-size: 1.5rem;
    font-weight: 600;
}

.vm-card p {
    color: var(--text-dark);
    line-height: 1.6;
    margin: 0;
    font-size: 1.05rem;
}

.core-values {
    margin: 4rem 0;
}

.core-values h2 {
    color: var(--secondary-color);
    text-align: center;
    font-size: 2.2rem;
    margin-bottom: 3rem;
    font-weight: 600;
}

.values-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.value-item {
    background: white;
    padding: 2.5rem;
    border-radius: 20px;
    box-shadow: 0 8px 25px var(--shadow-light);
    text-align: center;
    transition: all 0.3s ease;
}

.value-item:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 40px var(--shadow-medium);
}

.value-item h3 {
    color: var(--secondary-color);
    margin: 0 0 1.5rem 0;
    font-size: 1.4rem;
    font-weight: 600;
}

.value-item p {
    color: var(--text-dark);
    margin: 0;
    line-height: 1.5;
    font-size: 1rem;
}

.management-section {
    margin: 4rem 0;
}

.management-section h2 {
    color: var(--secondary-color);
    text-align: center;
    font-size: 2.2rem;
    margin-bottom: 3rem;
    font-weight: 600;
}

.management-card {
    background: white;
    padding: 3rem;
    border-radius: 20px;
    box-shadow: 0 8px 25px var(--shadow-light);
    max-width: 800px;
    margin: 0 auto;
}

.management-card h3 {
    color: var(--primary-color);
    margin: 0 0 2rem 0;
    text-align: center;
    font-size: 1.6rem;
    font-weight: 600;
}

.leader-info h4 {
    color: var(--secondary-color);
    margin: 2rem 0 1rem 0;
    font-size: 1.3rem;
    font-weight: 600;
}

.leader-info p {
    color: var(--text-dark);
    line-height: 1.6;
    margin-bottom: 1rem;
    font-size: 1.1rem;
}

.facilities-section {
    margin: 4rem 0;
}

.facilities-section h2 {
    color: var(--secondary-color);
    text-align: center;
    font-size: 2.2rem;
    margin-bottom: 3rem;
    font-weight: 600;
}

.facilities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.facility-item {
    background: linear-gradient(135deg, var(--warm-white), var(--light-blue));
    padding: 2.5rem;
    border-radius: 20px;
    box-shadow: 0 8px 25px var(--shadow-light);
    text-align: center;
    transition: all 0.3s ease;
}

.facility-item:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 40px var(--shadow-medium);
}

.facility-item h3 {
    color: var(--secondary-color);
    margin: 0 0 1.5rem 0;
    font-size: 1.4rem;
    font-weight: 600;
}

.facility-item p {
    color: var(--text-dark);
    margin: 0;
    line-height: 1.5;
    font-size: 1rem;
}

@media (max-width: 768px) {
    .vm-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .values-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .facilities-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .about-hero h1 {
        font-size: 2.2rem;
    }
    
    .hero-subtitle {
        font-size: 1.1rem;
    }
}
</style>

<?php require_once 'includes/footer.php'; ?>
