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
$skill_name = $level = "";
$skill_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($skill_id) {
    $query = "SELECT * FROM skills WHERE id = :id AND user_id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $skill_id);
    $stmt->bindParam(":user_id", $user_id);
    $stmt->execute();
    $skill = $stmt->fetch(PDO::FETCH_ASSOC);
    $skill_name = $skill['skill'];
    $level = $skill['level'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $skill = $_POST["skill"];
    $level = $_POST["level"];

    if ($skill_id) {
        $query = "UPDATE skills SET skill = :skill, level = :level WHERE id = :id AND user_id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":id", $skill_id);
    } else {
        $query = "INSERT INTO skills (user_id, skill, level) VALUES (:user_id, :skill, :level)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
    }

    $stmt->bindParam(":skill", $skill);
    $stmt->bindParam(":level", $level);
    
    $stmt->execute();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Skill</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <form action="" method="POST">
        <label>Skill Name:</label>
        <input type="text" name="skill" value=""><br>

        <label>Level (1-5):</label>
        <input type="number" name="level" min="1" max="5" value="<?= htmlspecialchars($level) ?>"><br>

        <button type="submit">Save</button>
    </form>
</body>
</html>
