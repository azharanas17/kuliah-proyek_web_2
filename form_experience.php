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
$title = $company = $description = $start_date = $end_date = "";
$experience_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($experience_id) {
    $query = "SELECT * FROM experience WHERE id = :id AND user_id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $experience_id);
    $stmt->bindParam(":user_id", $user_id);
    $stmt->execute();
    $experience = $stmt->fetch(PDO::FETCH_ASSOC);
    $title = $experience['title'];
    $company = $experience['company'];
    $description = $experience['description'];
    $start_date = $experience['start_date'];
    $end_date = $experience['end_date'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $company = $_POST["company"];
    $description = $_POST["description"];
    $start_date = $_POST["start_date"];
    $end_date = $_POST["end_date"];

    if ($experience_id) {
        $query = "UPDATE experience SET title = :title, company = :company, description = :description, start_date = :start_date, end_date = :end_date WHERE id = :id AND user_id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":id", $experience_id);
    } else {
        $query = "INSERT INTO experience (user_id, title, company, description, start_date, end_date) VALUES (:user_id, :title, :company, :description, :start_date, :end_date)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
    }

    $stmt->bindParam(":title", $title);
    $stmt->bindParam(":company", $company);
    $stmt->bindParam(":description", $description);
    $stmt->bindParam(":start_date", $start_date);
    $stmt->bindParam(":end_date", $end_date);
    
    $stmt->execute();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Experience</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <form action="" method="POST">
        <label>Title:</label>
        <input type="text" name="title" value="<?= htmlspecialchars($title) ?>"><br>

        <label>Company:</label>
        <input type="text" name="company" value="<?= htmlspecialchars($company) ?>"><br>

        <label>Description:</label>
        <textarea name="description"><?= htmlspecialchars($description) ?></textarea><br>

        <label>Start Date:</label>
        <input type="date" name="start_date" value="<?= htmlspecialchars($start_date) ?>"><br>

        <label>End Date:</label>
        <input type="date" name="end_date" value="<?= htmlspecialchars($end_date) ?>"><br>

        <button type="submit">Save</button>
    </form>
</body>
</html>