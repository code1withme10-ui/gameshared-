</div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3><i class="fas fa-baby"></i> Tiny Tots Creche</h3>
                    <p>Nurturing young minds with love, care, and quality education.</p>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h4><i class="fas fa-link"></i> Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="/"><i class="fas fa-chevron-right"></i> Home</a></li>
                        <li><a href="/about"><i class="fas fa-chevron-right"></i> About Us</a></li>
                        <li><a href="/admission"><i class="fas fa-chevron-right"></i> Admission</a></li>
                        <li><a href="/gallery"><i class="fas fa-chevron-right"></i> Gallery</a></li>
                        <li><a href="/contact"><i class="fas fa-chevron-right"></i> Contact</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4><i class="fas fa-clock"></i> Operating Hours</h4>
                    <ul class="hours-list">
                        <li><span>Monday - Friday:</span> 7:00 AM - 5:00 PM</li>
                        <li><span>Saturday:</span> 8:00 AM - 12:00 PM</li>
                        <li><span>Sunday:</span> Closed</li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4><i class="fas fa-map-marker-alt"></i> Contact Info</h4>
                    <ul class="contact-list">
                        <li><i class="fas fa-map-marker-alt"></i> 4 Copper Street, Musina, Limpopo, 0900</li>
                        <li><i class="fas fa-phone"></i> 081 421 0084</li>
                        <li><i class="fas fa-envelope"></i> mollerv40@gmail.com</li>
                        <li><i class="fas fa-globe"></i> EMIS NR: 973304431</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="footer-bottom-content">
                    <p>&copy; <?= date('Y') ?> Tiny Tots Creche. All rights reserved.</p>
                    <p>Designed with <i class="fas fa-heart" style="color: #ff6b6b;"></i> for the little ones</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="/public/js/script.js"></script>
    
    <!-- Additional Scripts for Specific Pages -->
    <?php if (isset($requireScripts)): ?>
        <?php foreach ($requireScripts as $script): ?>
            <script src="/public/js/<?= $script ?>.js"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- CSRF Token for AJAX requests -->
    <script>
        window.csrfToken = '<?= $this->generateCsrfToken() ?? '' ?>';
    </script>
</body>
</html>
