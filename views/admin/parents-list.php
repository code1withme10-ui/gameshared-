<div class="content-wrapper">
    <!-- Hero Section -->
    <section class="page-hero">
        <div class="hero-content">
            <h1>Parents List</h1>
            <p>View and manage all registered parents and their documents</p>
        </div>
    </section>

    <div class="admin-container">
        <!-- Search and Filters -->
        <div class="search-section">
            <div class="search-container">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="parentSearch" placeholder="Search parents by name, email, or ID...">
                </div>
                <div class="filter-stats">
                    <span class="total-count">
                        <i class="fas fa-users"></i>
                        Total Parents: <?= count($parents) ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Parents Table -->
        <div class="parents-table-container">
            <div class="table-header">
                <div>Parent Name</div>
                <div>Contact Information</div>
                <div>Applications</div>
                <div>Documents</div>
                <div>Actions</div>
            </div>

            <?php if (empty($parents)): ?>
                <div class="no-data">
                    <i class="fas fa-users"></i>
                    <h3>No Parents Found</h3>
                    <p>No parents have been registered yet.</p>
                    <a href="/admin/register-parent" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Register First Parent
                    </a>
                </div>
            <?php else: ?>
                <?php foreach ($parents as $parent): ?>
                    <div class="table-row">
                        <div>
                            <div class="parent-info">
                                <span class="parent-name"><?= htmlspecialchars($parent['firstName'] . ' ' . $parent['surname']) ?></span>
                                <span class="parent-id">ID: <?= htmlspecialchars($parent['idNumber'] ?? 'N/A') ?></span>
                            </div>
                        </div>
                        <div>
                            <div class="contact-info">
                                <span class="email-small"><?= htmlspecialchars($parent['email']) ?></span>
                                <span class="phone"><?= htmlspecialchars($parent['phone']) ?></span>
                            </div>
                        </div>
                        <div>
                            <span class="app-count"><?= $parent['application_count'] ?> applications</span>
                        </div>
                        <div>
                            <div class="document-indicators">
                                <?php if ($parent['document_count'] > 0): ?>
                                    <span class="doc-count">
                                        <i class="fas fa-file-alt"></i>
                                        <?= $parent['document_count'] ?> documents
                                    </span>
                                    <a href="/admin/parent-documents?parent_id=<?= urlencode($parent['id']) ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> View Documents
                                    </a>
                                <?php else: ?>
                                    <span class="no-docs">No documents</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div>
                            <div class="action-buttons-inline">
                                <a href="/admin/parent-documents?parent_id=<?= urlencode($parent['id']) ?>" class="btn-small btn-primary">
                                    <i class="fas fa-file-alt"></i> Documents
                                </a>
                                <a href="mailto:<?= htmlspecialchars($parent['email']) ?>" class="btn-small btn-secondary">
                                    <i class="fas fa-envelope"></i> Email
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
/* Parents List Styles */
.search-section {
    background: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.search-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
}

.search-box {
    position: relative;
    flex: 1;
    max-width: 400px;
}

.search-box i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
}

.search-box input {
    width: 100%;
    padding: 12px 15px 12px 45px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.search-box input:focus {
    outline: none;
    border-color: var(--primary-color);
}

.filter-stats {
    display: flex;
    align-items: center;
    gap: 15px;
}

.total-count {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
}

.parents-table-container {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.table-header {
    display: grid;
    grid-template-columns: 2fr 1.5fr 1fr 1.5fr 1.2fr;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px 20px;
    font-weight: 600;
}

.table-row {
    display: grid;
    grid-template-columns: 2fr 1.5fr 1fr 1.5fr 1.2fr;
    padding: 20px;
    border-bottom: 1px solid #e9ecef;
    align-items: center;
    transition: background-color 0.3s ease;
}

.table-row:hover {
    background-color: #f8f9fa;
}

.parent-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.parent-name {
    font-weight: 600;
    color: var(--text-dark);
}

.parent-id {
    font-size: 0.85rem;
    color: var(--text-muted);
}

.contact-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.email-small {
    font-size: 0.85rem;
    color: var(--primary-color);
}

.phone {
    font-size: 0.9rem;
    color: var(--text-dark);
}

.app-count {
    background: #e3f2fd;
    color: #1976d2;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 500;
}

.document-indicators {
    display: flex;
    flex-direction: column;
    gap: 8px;
    align-items: flex-start;
}

.doc-count {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.85rem;
    color: var(--success-color);
    font-weight: 500;
}

.no-data {
    text-align: center;
    padding: 60px 20px;
    color: var(--text-muted);
}

.no-data i {
    font-size: 4rem;
    color: #ccc;
    margin-bottom: 20px;
}

.no-data h3 {
    color: var(--text-muted);
    margin-bottom: 15px;
}

.no-data p {
    margin-bottom: 25px;
    font-style: italic;
}

@media (max-width: 768px) {
    .search-container {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-box {
        max-width: 100%;
    }
    
    .table-header,
    .table-row {
        grid-template-columns: 1fr;
        gap: 10px;
    }
    
    .table-header > div,
    .table-row > div {
        padding: 10px;
    }
    
    .action-buttons-inline {
        justify-content: center;
    }
}
</style>

<script>
// Live search functionality
document.getElementById('parentSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('.table-row');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
