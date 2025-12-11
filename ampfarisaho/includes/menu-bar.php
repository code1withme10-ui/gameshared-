<nav class="menu-bar">
    <ul>
        <li><a href="index.php?page=home">Home</a></li>
        <li><a href="index.php?page=about">About</a></li>
        <li><a href="index.php?page=gallery">Gallery</a></li>
        <li><a href="index.php?page=code_of_conduct">Code of Conduct</a></li>
        <li><a href="index.php?page=admission">Admission</a></li>
        <li><a href="index.php?page=help">Help</a></li>

        <?php if(isset($_SESSION['parent'])): ?>
            <li><a href="index.php?page=parent_dashboard">Dashboard</a></li>
            <li><a href="index.php?page=progress_report">Progress Report</a></li>
            <li><a href="index.php?page=logout">Logout</a></li>
        <?php elseif(isset($_SESSION['headmaster'])): ?>
            <li><a href="index.php?page=headmaster_dashboard">Headmaster Dashboard</a></li>
            <li><a href="index.php?page=logout">Logout</a></li>
        <?php else: ?>
            <li><a href="index.php?page=login">Login</a></li>
        <?php endif; ?>
    </ul>
</nav>

<!-- Optional basic styling -->
<style>
.menu-bar {
    background: #4CAF50;
    padding: 10px;
}
.menu-bar ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-wrap: wrap;
}
.menu-bar li {
    margin-right: 15px;
}
.menu-bar a {
    color: white;
    text-decoration: none;
    font-weight: bold;
}
.menu-bar a:hover {
    text-decoration: underline;
}
</style>

