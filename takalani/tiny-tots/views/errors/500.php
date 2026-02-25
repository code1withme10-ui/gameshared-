<div class="content-wrapper">
    <div class="error-container">
        <div class="error-content">
            <div class="error-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h1>Server Error</h1>
            <p>Something went wrong on our end. We're working to fix it!</p>
            
            <div class="error-details">
                <h3>What happened?</h3>
                <p>There was an internal server error while processing your request. This could be due to:</p>
                <ul>
                    <li>Temporary server issues</li>
                    <li>Database connection problems</li>
                    <li>System maintenance</li>
                </ul>
            </div>
            
            <div class="error-actions">
                <a href="/" class="btn btn-primary">
                    <i class="fas fa-home"></i> Go Home
                </a>
                <a href="javascript:location.reload()" class="btn btn-outline">
                    <i class="fas fa-sync"></i> Try Again
                </a>
                <a href="/contact" class="btn btn-secondary">
                    <i class="fas fa-envelope"></i> Report Issue
                </a>
            </div>
            
            <div class="contact-info">
                <h3>Need immediate assistance?</h3>
                <p>Contact our support team:</p>
                <div class="contact-details">
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>081 421 0084</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>mollerv40@gmail.com</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* 500 Error Page Styles */
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
    color: #ff6b6b;
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

.error-details {
    text-align: left;
    background: var(--warm-white);
    padding: 2rem;
    border-radius: 15px;
    margin-bottom: 3rem;
}

.error-details h3 {
    color: var(--text-dark);
    margin-bottom: 1rem;
    font-size: 1.2rem;
    font-weight: 600;
}

.error-details p {
    color: var(--text-light);
    margin-bottom: 1rem;
}

.error-details ul {
    margin: 0;
    padding-left: 1.5rem;
}

.error-details li {
    color: var(--text-dark);
    margin-bottom: 0.5rem;
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

.btn-secondary {
    background: var(--primary-color);
    color: var(--text-dark);
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px var(--shadow-medium);
}

.contact-info {
    background: var(--light-blue);
    padding: 2rem;
    border-radius: 15px;
}

.contact-info h3 {
    color: var(--text-dark);
    margin-bottom: 1rem;
    font-size: 1.2rem;
    font-weight: 600;
}

.contact-info p {
    color: var(--text-light);
    margin-bottom: 1.5rem;
}

.contact-details {
    display: flex;
    justify-content: center;
    gap: 2rem;
    flex-wrap: wrap;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 0.8rem;
}

.contact-item i {
    color: var(--primary-color);
    font-size: 1.2rem;
}

.contact-item span {
    color: var(--text-dark);
    font-weight: 600;
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
    
    .contact-details {
        flex-direction: column;
        gap: 1rem;
    }
}
</style>
