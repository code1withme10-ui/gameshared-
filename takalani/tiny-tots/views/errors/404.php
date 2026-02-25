<div class="content-wrapper">
    <div class="error-container">
        <div class="error-content">
            <div class="error-icon">
                <i class="fas fa-search"></i>
            </div>
            <h1>Page Not Found</h1>
            <p>The page you're looking for doesn't exist or has been moved.</p>
            
            <div class="error-actions">
                <a href="/" class="btn btn-primary">
                    <i class="fas fa-home"></i> Go Home
                </a>
                <a href="javascript:history.back()" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Go Back
                </a>
            </div>
            
            <div class="helpful-links">
                <h3>Looking for something?</h3>
                <ul>
                    <li><a href="/"><i class="fas fa-home"></i> Home Page</a></li>
                    <li><a href="/about"><i class="fas fa-info-circle"></i> About Us</a></li>
                    <li><a href="/admission"><i class="fas fa-graduation-cap"></i> Admission</a></li>
                    <li><a href="/gallery"><i class="fas fa-images"></i> Gallery</a></li>
                    <li><a href="/contact"><i class="fas fa-envelope"></i> Contact</a></li>
                    <li><a href="/login"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
/* 404 Error Page Styles */
.error-container {
    max-width: 600px;
    margin: 4rem auto;
    padding: 0 1rem;
    text-align: center;
}

.error-content {
    background: white;
    padding: 3rem;
    border-radius: 20px;
    box-shadow: 0 8px 30px var(--shadow-light);
}

.error-icon {
    font-size: 6rem;
    color: var(--primary-color);
    margin-bottom: 2rem;
}

.error-content h1 {
    color: var(--text-dark);
    font-size: 2.5rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

.error-content p {
    color: var(--text-light);
    font-size: 1.2rem;
    margin-bottom: 3rem;
    line-height: 1.6;
}

.error-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-bottom: 3rem;
    flex-wrap: wrap;
}

.btn {
    padding: 1rem 2rem;
    border: none;
    border-radius: 25px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-primary {
    background: linear-gradient(45deg, var(--secondary-color), var(--accent-color));
    color: var(--text-dark);
    box-shadow: 0 4px 15px var(--shadow-light);
}

.btn-outline {
    background: transparent;
    color: var(--primary-color);
    border: 2px solid var(--primary-color);
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px var(--shadow-medium);
}

.helpful-links {
    text-align: left;
    max-width: 400px;
    margin: 0 auto;
}

.helpful-links h3 {
    color: var(--primary-color);
    margin-bottom: 1rem;
    font-size: 1.2rem;
    font-weight: 600;
}

.helpful-links ul {
    list-style: none;
    padding: 0;
}

.helpful-links li {
    margin-bottom: 0.8rem;
}

.helpful-links a {
    color: var(--text-dark);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.8rem;
    padding: 0.8rem;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.helpful-links a:hover {
    background: var(--warm-white);
    color: var(--primary-color);
    transform: translateX(5px);
}

.helpful-links a i {
    color: var(--primary-color);
    width: 20px;
    text-align: center;
}

@media (max-width: 768px) {
    .error-container {
        margin: 2rem auto;
        padding: 0 0.5rem;
    }
    
    .error-content {
        padding: 2rem 1.5rem;
    }
    
    .error-icon {
        font-size: 4rem;
    }
    
    .error-content h1 {
        font-size: 2rem;
    }
    
    .error-content p {
        font-size: 1rem;
    }
    
    .error-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .error-actions .btn {
        width: 100%;
        max-width: 250px;
        justify-content: center;
    }
}
</style>
