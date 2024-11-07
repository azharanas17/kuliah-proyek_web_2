<?php
session_start();
require 'config.php';
$database = new Database();
$db = $database->getConnection();

if (isset($_SESSION["user_id"])) {
    // echo "User ID: " . $_SESSION["user_id"];
    $user_id = $_SESSION["user_id"];
} else {
    echo "Sesi tidak ditemukan.";
    header("Location: login.php");
    exit();
}

$query = "SELECT * FROM profile WHERE user_id = :user_id";
$stmt = $db->prepare($query);
$stmt->bindParam(":user_id", $user_id);
$stmt->execute();
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

$queryExperience = "SELECT * FROM experience WHERE user_id = :user_id";
$stmtExp = $db->prepare($queryExperience);
$stmtExp->bindParam(":user_id", $user_id);
$stmtExp->execute();
$experiences = $stmtExp->fetchAll(PDO::FETCH_ASSOC);

$querySkills = "SELECT * FROM skills WHERE user_id = :user_id";
$stmtSkills = $db->prepare($querySkills);
$stmtSkills->bindParam(":user_id", $user_id);
$stmtSkills->execute();
$skills = $stmtSkills->fetchAll(PDO::FETCH_ASSOC);

$queryHobbies = "SELECT * FROM hobbies WHERE user_id = :user_id";
$stmtHobbies = $db->prepare($queryHobbies);
$stmtHobbies->bindParam(":user_id", $user_id);
$stmtHobbies->execute();
$hobbies = $stmtHobbies->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($profile['name']) ?>'s Portfolio</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/porto_style.css">
</head>
<body>
    <div class="container">
        <a href="admin.php">Edit Profil</a>
        <button class="toggle-theme" onclick="toggleTheme()">Toggle Mode Gelap</button>

        <h1><?= htmlspecialchars($profile['name']) ?></h1>
        <img src="<?= htmlspecialchars($profile['photo']) ?>" alt="Profile Photo" style="height: 10em; width: 10em;"><br>
        <p><?= htmlspecialchars($profile['bio']) ?></p>
        <p>Contact: <?= htmlspecialchars($profile['contact_info']) ?></p>

        <h2>Experience</h2>
        <?php foreach ($experiences as $exp): ?>
            <h3><?= htmlspecialchars($exp['title']) ?> at <?= htmlspecialchars($exp['company']) ?></h3>
            <p><?= htmlspecialchars($exp['description']) ?></p>
            <p><?= htmlspecialchars($exp['start_date']) ?> - <?= htmlspecialchars($exp['end_date']) ?></p>
        <?php endforeach; ?>

        <h2>Skills</h2>
        <?php foreach ($skills as $skill): ?>
            <p><?= htmlspecialchars($skill['skill']) ?> (Level: <?= htmlspecialchars($skill['level']) ?>)</p>
        <?php endforeach; ?>

        <h2>Hobbies</h2>
        <?php foreach ($hobbies as $hobby): ?>
            <p><?= htmlspecialchars($hobby['hobby']) ?> - <?= htmlspecialchars($hobby['description']) ?></p>
        <?php endforeach; ?>
    </div>

    <script src="js/script.js"></script>
</body>
</html>
