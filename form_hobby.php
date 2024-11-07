<?php
session_start();
require 'config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$user_id = $_SESSION["user_id"];
$hobby = $description = "";
$hobby_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($hobby_id) {
    $query = "SELECT * FROM hobbies WHERE id = :id AND user_id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $hobby_id);
    $stmt->bindParam(":user_id", $user_id);
    $stmt->execute();
    $hobby = $stmt->fetch(PDO::FETCH_ASSOC);
    $hobby = $hobby['hobby'];
    $description = $hobby['description'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hobby = $_POST["hobby"];
    $description = $_POST["description"];

    if ($hobby_id) {
        $query = "UPDATE hobbies SET hobby = :hobby, description = :description WHERE id = :id AND user_id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":id", $hobby_id);
    } else {
        $query = "INSERT INTO hobbies (user_id, hobby, description) VALUES (:user_id, :hobby, :description)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
    }

    $stmt->bindParam(":hobby", $hobby);
    $stmt->bindParam(":description", $description);
    
    $stmt->execute();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Hobby</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <form action="" method="POST">
        <label>Hobby Name:</label>
        <input type="text" name="hobby" value="<?= htmlspecialchars($hobby) ?>"><br>

        <label>Description:</label>
        <textarea name="description"><?= htmlspecialchars($description) ?></textarea><br>

        <button type="submit">Save</button>
    </form>
</body>
</html>
