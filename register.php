<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = 'user'; // default role for new user

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $stmt = $pdo->prepare("INSERT INTO login (username, password) VALUES (:username, :password)");
    $stmt->execute(['username' => $username, 'password' => $hashed_password]);

    // Redirect to login page after successful sign-up
    header('Location: login.php');
    exit;
}
?>

<?php include 'header.php'; ?>

<main>
    <h1>Sign Up</h1>

    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br>

        <button type="submit">Sign Up</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p> <!-- In case the user already has an account or just created a new one -->
</main>

<?php include 'footer.php'; ?>
