<?php
session_start();
require 'config.php';
$database = new Database();
$db = $database->getConnection();

if (isset($_SESSION["user_id"])) {
    echo "User ID: " . $_SESSION["user_id"];
    $user_id = $_SESSION["user_id"];
} else {
    echo "Sesi tidak ditemukan.";
}

$query = "SELECT * FROM profile WHERE user_id = :user_id";
$stmt = $db->prepare($query);
$stmt->bindParam(":user_id", $_SESSION["user_id"]);
$stmt->execute();
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

$queryExperience = "SELECT * FROM experience WHERE user_id = :user_id";
$stmtExperience = $db->prepare($queryExperience);
$stmtExperience->bindParam(":user_id", $_SESSION["user_id"]);
$stmtExperience->execute();
$experiences = $stmtExperience->fetchAll(PDO::FETCH_ASSOC);

$querySkill = "SELECT * FROM skills WHERE user_id = :user_id";
$stmtSkill = $db->prepare($querySkill);
$stmtSkill->bindParam(":user_id", $_SESSION["user_id"]);
$stmtSkill->execute();
$skills = $stmtSkill->fetchAll(PDO::FETCH_ASSOC);

$queryHobby = "SELECT * FROM hobbies WHERE user_id = :user_id";
$stmtHobby = $db->prepare($queryHobby);
$stmtHobby->bindParam(":user_id", $_SESSION["user_id"]);
$stmtHobby->execute();
$hobbies = $stmtHobby->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $type = $_GET['type'];
    
    if ($type == "experience") {
        $deleteQuery = "DELETE FROM experience WHERE id = :id AND user_id = :user_id";
    } elseif ($type == "skill") {
        $deleteQuery = "DELETE FROM skills WHERE id = :id AND user_id = :user_id";
    } elseif ($type == "hobby") {
        $deleteQuery = "DELETE FROM hobbies WHERE id = :id AND user_id = :user_id";
    }
    
    if (isset($deleteQuery)) {
        $deleteStmt = $db->prepare($deleteQuery);
        $deleteStmt->bindParam(":id", $id);
        $deleteStmt->bindParam(":user_id", $_SESSION["user_id"]);
        $deleteStmt->execute();
        header("Location: admin.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Admin Panel</h1>
        <form method="POST" action="logout.php" style="display:inline;">
            <button type="submit">Logout</button>
        </form>
    </header>
    <h1>Edit Profil</h1>
    <form method="POST" action="update_profile.php" enctype="multipart/form-data">
        <label>Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($profile['name'] ?? '') ?>"><br>

        <label>Bio:</label>
        <textarea name="bio"><?= htmlspecialchars($profile['bio'] ?? '') ?></textarea><br>

        <label>Photo: (max 2mb)</label>
        <input type="file" name="photo"><br>

        <label>Contact Info:</label>
        <input type="text" name="contact_info" value="<?= htmlspecialchars($profile['contact_info'] ?? '') ?>"><br>

        <button type="submit">Update</button>
    </form>

    <h2>Experience</h2>
    <a href="form_experience.php">Add New Experience</a>
    <ul>
        <?php foreach ($experiences as $experience): ?>
            <li>
                <?= htmlspecialchars($experience['title']); ?> at <?= htmlspecialchars($experience['company']); ?>
                (<?= htmlspecialchars($experience['start_date']); ?> - <?= htmlspecialchars($experience['end_date']); ?>)
                <a href="?delete=<?= $experience['id']; ?>&type=experience" onclick="return confirm('Are you sure you want to delete this experience?');">Delete</a>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Skills</h2>
    <a href="form_skill.php">Add New Skill</a>
    <ul>
        <?php foreach ($skills as $skill): ?>
            <li>
                <?= htmlspecialchars($skill['skill']); ?>
                <a href="?delete=<?= $skill['id']; ?>&type=skill" onclick="return confirm('Are you sure you want to delete this skill?');">Delete</a>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Hobbies</h2>
    <a href="form_hobby.php">Add New Hobby</a>
    <ul>
        <?php foreach ($hobbies as $hobby): ?>
            <li>
                <?= htmlspecialchars($hobby['hobby']); ?>
                <a href="?delete=<?= $hobby['id']; ?>&type=hobby" onclick="return confirm('Are you sure you want to delete this hobby?');">Delete</a>
            </li>
        <?php endforeach; ?>
    </ul>

</body>
</html>
