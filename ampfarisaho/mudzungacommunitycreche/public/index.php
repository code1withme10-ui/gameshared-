<?php
require_once __DIR__ . '/../app/views/partials/header.php';
?>

<!-- TOP STRIP -->
<div class="top-strip">
    <h2>MUDZUNGA COMMUNITY CRECHE</h2>
    <p>Nurturing tiny hearts, shaping bright futures</p>
</div>

<?php require_once __DIR__ . '/../app/views/partials/navbar.php'; ?>

<div class="container">

    <!-- HERO PREMIUM -->
    <section class="hero-premium">
        <div class="hero-content">
            <h1>Building Bright Futures, One Child at a Time 🌟</h1>
            <p>
                A premium, loving environment in Venda where your child is 
                encouraged to discover, learn, and grow with absolute confidence.
            </p>
            <div class="hero-buttons">
                <a href="/register.php" class="btn btn-primary">Get Started</a>
                <a href="/login.php" class="btn btn-outline">Login</a>
            </div>
        </div>
    </section>

    <!-- INTERACTIVE COUNTERS -->
    <section class="stats-premium">
        <div class="stat-box">
            <h3 class="counter" data-target="39">0</h3><span>+</span>
            <p>Years of Excellence</p>
        </div>
        <div class="stat-box">
            <h3 class="counter" data-target="500">0</h3><span>+</span>
            <p>Happy Children</p>
        </div>
        <div class="stat-box">
            <h3 class="counter" data-target="10">0</h3><span>+</span>
            <p>Dedicated Staff</p>
        </div>
        <div class="stat-box">
            <h3 class="counter" data-target="100">0</h3><span>%</span>
            <p>Safe Environment</p>
        </div>
    </section>

    <!-- JS for Counters -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const counters = document.querySelectorAll('.counter');
            const speed = 100;

            counters.forEach(counter => {
                const updateCount = () => {
                    const target = +counter.getAttribute('data-target');
                    const count = +counter.innerText;
                    const inc = target / speed;

                    if (count < target) {
                        counter.innerText = Math.ceil(count + inc);
                        setTimeout(updateCount, 20);
                    } else {
                        counter.innerText = target;
                    }
                };
                
                const observer = new IntersectionObserver((entries) => {
                    if(entries[0].isIntersecting) {
                        updateCount();
                        observer.disconnect();
                    }
                }, { threshold: 0.5 });
                
                observer.observe(counter);
            });
        });
    </script>

    <!-- FEATURES PREMIUM -->
    <section class="features-premium">
        <div class="feature-box">
            <div class="icon">👶</div>
            <h3>Safe Environment</h3>
            <p>Secure, clean, and child-friendly spaces for peace of mind.</p>
        </div>

        <div class="feature-box">
            <div class="icon">📚</div>
            <h3>Early Learning</h3>
            <p>Fun, engaging activities that build strong foundations.</p>
        </div>

        <div class="feature-box">
            <div class="icon">❤️</div>
            <h3>Caring Staff</h3>
            <p>Dedicated teachers who truly care about every child.</p>
        </div>
    </section>

</div>

<?php
require_once __DIR__ . '/../app/views/partials/footer.php';
?>
