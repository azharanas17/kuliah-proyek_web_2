<?php
session_start();
require 'config.php';
$database = new Database();
$db = $database->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["username"]) && !empty($_POST["password"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];

        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {
            session_regenerate_id(true);
            $_SESSION["user_id"] = $user["id"];
            
            if (isset($_POST['remember'])) {
                setcookie("username", $username, time() + (86400 * 30), "/");
            }

            header("Location: admin.php");
            exit();
        } else {
            $error = "Username atau password salah!";
        }
    } else {
        $error = "Harap isi username dan password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" name="username" value="<?= htmlspecialchars($_COOKIE['username'] ?? '') ?>" required><br>

            <label for="password">Password:</label>
            <input type="password" name="password" required><br>

            <label>
                <input type="checkbox" name="remember"> Remember Me
            </label><br>

            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a>.</p>

        <?php if (isset($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
