<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="bg-gray-800 text-white p-4">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <a class="navbar-brand text-xl font-bold" href="index.php">Assignment 2</a>
        <div class="flex space-x-4">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a class="hover:text-gray-300" href="create.php">Create</a>
                <a class="hover:text-gray-300" href="logout.php">Logout</a>
            <?php else: ?>
                <a class="hover:text-gray-300" href="login.php">Login</a>
                <a class="hover:text-gray-300" href="register.php">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>