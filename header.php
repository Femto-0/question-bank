<?php
// header.php

// Check if the user is logged in as a student or mod
$is_logged_in = isset($_SESSION['username']);
$is_mod = isset($_SESSION['username']) && $_SESSION['role'] === 'mod'; // Check if it's a mod
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question Paper Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <?php if ($is_logged_in): ?>
                    <li><a href="upload.php">Upload Paper</a></li>
                    <?php if ($is_mod): ?>
                        <li><a href="mod.php">Mod Page</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
