<div class="content-wrapper">
    <div class="admin-container">
        <div class="admin-header">
            <h1><i class="fas fa-users"></i> Manage Users</h1>
            <p>Create and manage user accounts</p>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-info">
                    <h3><?= $stats['total'] ?></h3>
                    <p>Total Users</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">👨‍👩‍👧‍👦</div>
                <div class="stat-info">
                    <h3><?= $stats['parents'] ?></h3>
                    <p>Parents</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">👨‍💼</div>
                <div class="stat-info">
                    <h3><?= $stats['headmasters'] ?></h3>
                    <p>Headmasters</p>
                </div>
            </div>
        </div>
        
        <div class="users-section">
            <div class="section-header">
                <h2><i class="fas fa-list"></i> User Accounts</h2>
                <div class="header-actions">
                    <form method="GET" class="filter-form">
                        <select name="role" onchange="this.form.submit()">
                            <option value="all" <?= $role === 'all' ? 'selected' : '' ?>>All Users</option>
                            <option value="parent" <?= $role === 'parent' ? 'selected' : '' ?>>Parents</option>
                            <option value="headmaster" <?= $role === 'headmaster' ? 'selected' : '' ?>>Headmasters</option>
                        </select>
                    </form>
                    
                    <a href="/admin/create-user" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Create User
                    </a>
                </div>
            </div>
            
            <div class="users-table">
                <div class="table-header">
                    <div>Username</div>
                    <div>Name</div>
                    <div>Email</div>
                    <div>Role</div>
                    <div>Created</div>
                    <div>Actions</div>
                </div>
                
                <?php if (empty($users)): ?>
                    <div class="no-data">
                        <i class="fas fa-users"></i>
                        <h3>No users found</h3>
                        <p>There are no users with the selected role.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <div class="table-row">
                            <div>
                                <span class="username"><?= htmlspecialchars($user['username']) ?></span>
                            </div>
                            <div>
                                <span class="user-name"><?= htmlspecialchars($user['name']) ?></span>
                            </div>
                            <div>
                                <span class="user-email"><?= htmlspecialchars($user['email']) ?></span>
                            </div>
                            <div>
                                <span class="role-badge role-<?= htmlspecialchars($user['role']) ?>">
                                    <?= htmlspecialchars(ucfirst($user['role'])) ?>
                                </span>
                            </div>
                            <div>
                                <span class="create-date"><?= date('M j, Y', strtotime($user['created_at'])) ?></span>
                            </div>
                            <div>
                                <div class="action-buttons">
                                    <button onclick="editUser('<?= htmlspecialchars($user['id']) ?>')" 
                                            class="btn-small btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <?php if ($user['id'] !== $_SESSION['user']['id']): ?>
                                        <button onclick="deleteUser('<?= htmlspecialchars($user['id']) ?>')" 
                                                class="btn-small btn-delete">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
/* Users Management Styles */
.admin-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

.admin-header {
    text-align: center;
    margin-bottom: 3rem;
}

.admin-header h1 {
    color: var(--secondary-color);
    font-size: 2.5rem;
    margin: 0 0 0.5rem 0;
    font-weight: 600;
}

.admin-header p {
    color: var(--text-light);
    font-size: 1.1rem;
    margin: 0;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.stat-card {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 8px 25px var(--shadow-light);
    display: flex;
    align-items: center;
    gap: 1.5rem;
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    font-size: 3rem;
    width: 60px;
    text-align: center;
}

.stat-info h3 {
    color: var(--text-dark);
    font-size: 2rem;
    margin: 0 0 0.5rem 0;
    font-weight: 600;
}

.stat-info p {
    color: var(--text-light);
    margin: 0;
    font-size: 0.9rem;
}

.users-section {
    background: white;
    border-radius: 20px;
    box-shadow: 0 8px 30px var(--shadow-light);
    overflow: hidden;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 2rem;
    border-bottom: 1px solid var(--light-blue);
    flex-wrap: wrap;
    gap: 1rem;
}

.section-header h2 {
    color: var(--primary-color);
    margin: 0;
    font-size: 1.8rem;
    font-weight: 600;
    display: flex;
    align-items: center;
}

.section-header h2 i {
    margin-right: 0.8rem;
}

.header-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.filter-form select {
    padding: 0.8rem 1rem;
    border: 2px solid var(--light-blue);
    border-radius: 10px;
    font-size: 1rem;
    background: var(--warm-white);
}

.users-table {
    padding: 0 2rem 2rem;
}

.table-header {
    display: grid;
    grid-template-columns: 1.5fr 1.5fr 2fr 1fr 1fr 1.5fr;
    gap: 1rem;
    padding: 1rem;
    background: var(--light-blue);
    border-radius: 10px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 1rem;
}

.table-row {
    display: grid;
    grid-template-columns: 1.5fr 1.5fr 2fr 1fr 1fr 1.5fr;
    gap: 1rem;
    padding: 1rem;
    border-bottom: 1px solid var(--light-blue);
    align-items: center;
    transition: background-color 0.3s ease;
}

.table-row:hover {
    background: var(--warm-white);
}

.username {
    font-family: monospace;
    font-weight: 600;
    color: var(--primary-color);
}

.user-name {
    font-weight: 600;
    color: var(--text-dark);
}

.user-email {
    font-size: 0.9rem;
    color: var(--text-light);
}

.role-badge {
    padding: 0.3rem 0.8rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: capitalize;
}

.role-parent {
    background: var(--primary-color);
    color: white;
}

.role-headmaster {
    background: var(--secondary-color);
    color: var(--text-dark);
}

.create-date {
    font-size: 0.9rem;
    color: var(--text-light);
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-small {
    padding: 0.4rem 0.8rem;
    border: none;
    border-radius: 8px;
    font-size: 0.75rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.btn-edit {
    background: var(--primary-color);
    color: white;
}

.btn-delete {
    background: #ff6b6b;
    color: white;
}

.btn-small:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.no-data {
    text-align: center;
    padding: 4rem 2rem;
    color: var(--text-light);
}

.no-data i {
    font-size: 4rem;
    margin-bottom: 1rem;
    color: var(--light-blue);
}

.no-data h3 {
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

@media (max-width: 768px) {
    .admin-container {
        padding: 1rem 0.5rem;
    }
    
    .section-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .header-actions {
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .table-header,
    .table-row {
        grid-template-columns: 1fr;
        gap: 0.5rem;
        font-size: 0.8rem;
    }
    
    .table-row > div {
        padding: 0.5rem;
        border-bottom: 1px solid var(--light-blue);
    }
    
    .action-buttons {
        justify-content: center;
        margin-top: 1rem;
    }
}
</style>

<script>
function editUser(id) {
    // In a real application, this would open an edit modal or redirect to edit page
    alert(`Edit user functionality for user ID: ${id}`);
}

function deleteUser(id) {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        // In a real application, this would make an AJAX call
        fetch('/admin/delete-user', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': window.csrfToken
            },
            body: JSON.stringify({
                id: id
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('User deleted successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the user.');
        });
    }
}
</script>
