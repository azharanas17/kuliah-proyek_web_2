<?php
require 'config.php';

$database = new Database();
$db = $database->getConnection();

$username = $email = $password = $confirm_password = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    if (empty($username)) {
        $errors[] = "Username is required.";
    }

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        $query = "SELECT id FROM users WHERE username = :username OR email = :email";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $errors[] = "Username or email already taken.";
        } else {
            $password_hash = password_hash($password, PASSWORD_BCRYPT);

            $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $password_hash);

            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $errors[] = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Register</h2>
    <?php if (!empty($errors)) : ?>
        <ul>
            <?php foreach ($errors as $error) : ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    
    <form action="" method="POST">
        <label>Username:</label>
        <input type="text" name="username" value="<?= htmlspecialchars($username) ?>"><br>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>"><br>

        <label>Password:</label>
        <input type="password" name="password"><br>

        <label>Confirm Password:</label>
        <input type="password" name="confirm_password"><br>

        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a>.</p>
</body>
</html>
