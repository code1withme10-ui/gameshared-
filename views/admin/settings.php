<div class="content-wrapper">
    <div class="settings-container">
        <div class="settings-header">
            <h1><i class="fas fa-cog"></i> System Settings</h1>
            <p>Configure system-wide settings and preferences</p>
        </div>
        
        <form id="settingsForm" method="POST" action="/admin/settings" class="settings-form">
            <div class="settings-section">
                <h2><i class="fas fa-info-circle"></i> General Settings</h2>
                
                <div class="form-group">
                    <label for="site_name">
                        <i class="fas fa-building"></i> Site Name
                    </label>
                    <input type="text" id="site_name" name="site_name" 
                           value="<?= htmlspecialchars($settings['site_name'] ?? 'Tiny Tots Creche') ?>"
                           placeholder="Enter site name">
                </div>
                
                <div class="form-group">
                    <label for="contact_email">
                        <i class="fas fa-envelope"></i> Contact Email
                    </label>
                    <input type="email" id="contact_email" name="contact_email" 
                           value="<?= htmlspecialchars($settings['contact_email'] ?? 'mollerv40@gmail.com') ?>"
                           placeholder="contact@example.com">
                </div>
                
                <div class="form-group">
                    <label for="contact_phone">
                        <i class="fas fa-phone"></i> Contact Phone
                    </label>
                    <input type="tel" id="contact_phone" name="contact_phone" 
                           value="<?= htmlspecialchars($settings['contact_phone'] ?? '081 421 0084') ?>"
                           placeholder="081 421 0084">
                </div>
                
                <div class="form-group">
                    <label for="contact_address">
                        <i class="fas fa-map-marker-alt"></i> Contact Address
                    </label>
                    <input type="text" id="contact_address" name="contact_address" 
                           value="<?= htmlspecialchars($settings['contact_address'] ?? '4 Copper Street, Musina, Limpopo, 0900') ?>"
                           placeholder="Enter address">
                </div>
            </div>
            
            <div class="settings-section">
                <h2><i class="fas fa-graduation-cap"></i> Admission Settings</h2>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" id="admissions_open" name="admissions_open" 
                               <?= isset($settings['admissions_open']) && $settings['admissions_open'] ? 'checked' : '' ?>>
                        <span class="checkmark"></span>
                        Admissions Open
                    </label>
                    <small class="form-help">Enable/disable new admission applications</small>
                </div>
                
                <h3>Grade Capacity Limits</h3>
                <div class="capacity-grid">
                    <div class="capacity-item">
                        <label for="max_grade_r">
                            <i class="fas fa-child"></i> Grade R
                        </label>
                        <input type="number" id="max_grade_r" name="max_grade_r" min="1" max="100"
                               value="<?= htmlspecialchars($settings['max_grade_r'] ?? 30) ?>"
                               placeholder="30">
                    </div>
                    
                    <div class="capacity-item">
                        <label for="max_grade_1">
                            <i class="fas fa-book"></i> Grade 1
                        </label>
                        <input type="number" id="max_grade_1" name="max_grade_1" min="1" max="100"
                               value="<?= htmlspecialchars($settings['max_grade_1'] ?? 30) ?>"
                               placeholder="30">
                    </div>
                    
                    <div class="capacity-item">
                        <label for="max_grade_2">
                            <i class="fas fa-book-open"></i> Grade 2
                        </label>
                        <input type="number" id="max_grade_2" name="max_grade_2" min="1" max="100"
                               value="<?= htmlspecialchars($settings['max_grade_2'] ?? 30) ?>"
                               placeholder="30">
                    </div>
                    
                    <div class="capacity-item">
                        <label for="max_grade_3">
                            <i class="fas fa-graduation-cap"></i> Grade 3
                        </label>
                        <input type="number" id="max_grade_3" name="max_grade_3" min="1" max="100"
                               value="<?= htmlspecialchars($settings['max_grade_3'] ?? 30) ?>"
                               placeholder="30">
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Settings
                </button>
                <button type="reset" class="btn btn-outline">
                    <i class="fas fa-undo"></i> Reset
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Settings Page Styles */
.settings-container {
    max-width: 800px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.settings-header {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: var(--text-dark);
    padding: 2.5rem;
    border-radius: 20px 20px 0 0;
    text-align: center;
}

.settings-header h1 {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.settings-header p {
    font-size: 1.1rem;
    opacity: 0.9;
    margin: 0;
}

.settings-form {
    background: white;
    padding: 3rem;
    border-radius: 0 0 20px 20px;
    box-shadow: 0 8px 30px var(--shadow-light);
}

.settings-section {
    margin-bottom: 3rem;
}

.settings-section h2 {
    color: var(--primary-color);
    margin-bottom: 2rem;
    font-size: 1.5rem;
    font-weight: 600;
    display: flex;
    align-items: center;
}

.settings-section h2 i {
    margin-right: 0.8rem;
}

.settings-section h3 {
    color: var(--secondary-color);
    margin-bottom: 1.5rem;
    font-size: 1.2rem;
    font-weight: 600;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: flex;
    align-items: center;
    color: var(--text-dark);
    font-weight: 500;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.form-group label i {
    margin-right: 0.5rem;
    color: var(--primary-color);
}

.form-group input[type="text"],
.form-group input[type="email"],
.form-group input[type="tel"],
.form-group input[type="number"] {
    width: 100%;
    padding: 1rem;
    border: 2px solid var(--light-blue);
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: var(--warm-white);
}

.form-group input[type="checkbox"] {
    margin-right: 0.8rem;
}

.form-group label[for="admissions_open"] {
    cursor: pointer;
    display: flex;
    align-items: center;
}

.checkmark {
    display: none;
}

.form-help {
    font-size: 0.85rem;
    color: var(--text-light);
    margin-top: 0.5rem;
    font-style: italic;
    display: block;
}

.capacity-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.capacity-item {
    display: flex;
    flex-direction: column;
}

.capacity-item label {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
}

.capacity-item input {
    padding: 0.8rem;
    border: 2px solid var(--light-blue);
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: var(--warm-white);
}

.form-group input:focus,
.capacity-item input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 8px rgba(135, 206, 235, 0.3);
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 2px solid var(--light-blue);
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

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px var(--shadow-medium);
}

.btn-outline {
    background: transparent;
    color: var(--primary-color);
    border: 2px solid var(--primary-color);
}

.btn-outline:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .settings-container {
        margin: 1rem auto;
        padding: 0 0.5rem;
    }
    
    .settings-header {
        padding: 2rem 1.5rem;
    }
    
    .settings-form {
        padding: 2rem 1.5rem;
    }
    
    .capacity-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    .form-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .form-actions .btn {
        width: 100%;
        max-width: 300px;
        justify-content: center;
    }
}
</style>
