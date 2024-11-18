<?php
// login.php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username exists in the login table (student)
    $stmt = $pdo->prepare("SELECT * FROM login WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user) {
        // Verify the password using password_verify
        if (password_verify($password, $user['password'])) {
            // Password is correct, set session variables
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'student'; // Default role is 'student'
            header("Location: index.php"); // Redirect to homepage for student
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        // Check if it's a mod login
        $stmt = $pdo->prepare("SELECT * FROM mods WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Password is correct for mod, set session variables
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'mod'; // Set role as 'mod' for moderators
            header("Location: mod.php"); // Redirect to mod page
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
  <main>
    <h1>Login</h1>
    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Sign up here</a></p>  <!-- Takes user to the registration page -->

    <?php if (isset($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
</main>  
</body>
</html>


<?php include 'footer.php'; ?>
