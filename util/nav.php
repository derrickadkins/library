<?php
/*
 * This excerpt is responsible for generating the navigation bar for the user interface.
 * 
 * It uses Bootstrap's navbar component to create a responsive navigation bar with a 
 * brand and a toggle button for smaller screens.
 * The navigation bar contains links to the dashboard and the books page.
 * 
 * The script uses PHP to dynamically add the 'active' class to the navigation item that 
 * corresponds to the current page.
 * This is done by comparing the basename of the current script (obtained from `
 * $_SERVER['PHP_SELF']`) with the names of the pages.
 * 
 * The navigation bar also contains a link to the logout script, which is aligned to the 
 * right using Bootstrap's 'ml-auto' class.
 * 
 * The excerpt ends with script tags that include jQuery, Popper.js, and Bootstrap's 
 * JavaScript file.
 * These are necessary for the responsive behavior of the navigation bar.
 */
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="dashboard.php"><img src="icon.png" style="width: 30px; height: 30px;"/> Library</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                <a class="nav-link" href="dashboard.php">Dashboard</a>
            </li>
            <?php if($_SESSION['admin'] === true): ?>
                <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'members.php' ? 'active' : ''; ?>">
                    <a class="nav-link" href="members.php">Members</a>
                </li>
            <?php endif; ?>
            <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'books.php' ? 'active' : ''; ?>">
                <a class="nav-link" href="books.php">Books</a>
            </li>
            <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' && isset($_GET['id']) && $_SESSION['rec_id'] == $_GET['id'] ? 'active' : ''; ?>">
                <a class="nav-link" href="profile.php?id=<?php echo $_SESSION['rec_id']; ?>">Profile</a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="../php/auth/logout.php">Sign Out</a>
            </li>
        </ul>
    </div>
</nav>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>