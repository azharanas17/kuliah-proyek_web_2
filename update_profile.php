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
    exit();
}

$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $bio = $_POST['bio'] ?? '';
    $contact_info = $_POST['contact_info'] ?? '';

    if (empty($name)) {
        $errors[] = "Name is required.";
    }

    $uploadPath = null;
    if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) {
        $fileName = $_FILES["photo"]["name"];
        $fileTmpPath = $_FILES["photo"]["tmp_name"];
        $fileSize = $_FILES["photo"]["size"];

        if ($fileSize > 2 * 1024 * 1024) {
            $errors[] = "File size should not exceed 2 MB.";
        }

        $uploadPath = 'uploads/' . basename($fileName);

        if (empty($errors) && !move_uploaded_file($fileTmpPath, $uploadPath)) {
            $errors[] = "Error uploading file.";
        }
    }

    if (empty($errors)) {
        $queryCheck = "SELECT id FROM profile WHERE user_id = :user_id";
        $stmtCheck = $db->prepare($queryCheck);
        $stmtCheck->bindParam(":user_id", $user_id);
        $stmtCheck->execute();
        $profileExists = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if (!$profileExists) {
            $queryInsert = "INSERT INTO profile (user_id, name, bio, contact_info, photo) 
                            VALUES (:user_id, :name, :bio, :contact_info, :photo)";
            $stmtInsert = $db->prepare($queryInsert);
            $stmtInsert->bindParam(":user_id", $user_id);
            $stmtInsert->bindParam(":name", $name);
            $stmtInsert->bindParam(":bio", $bio);
            $stmtInsert->bindParam(":contact_info", $contact_info);
            $stmtInsert->bindParam(":photo", $uploadPath);
            $stmtInsert->execute();

        } else {
            $queryUpdate = "UPDATE profile SET name = :name, bio = :bio, contact_info = :contact_info" 
                         . ($uploadPath ? ", photo = :photo" : "") 
                         . " WHERE user_id = :user_id";
            $stmtUpdate = $db->prepare($queryUpdate);
            $stmtUpdate->bindParam(":name", $name);
            $stmtUpdate->bindParam(":bio", $bio);
            $stmtUpdate->bindParam(":contact_info", $contact_info);
            $stmtUpdate->bindParam(":user_id", $user_id);
            if ($uploadPath) {
                $stmtUpdate->bindParam(":photo", $uploadPath);
            }
            $stmtUpdate->execute();
        }

        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Profile</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Update Profile</h1>
    <?php if (!empty($errors)) : ?>
        <ul>
            <?php foreach ($errors as $error) : ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($name ?? '') ?>">

        <label for="bio">Bio:</label>
        <textarea name="bio" id="bio"><?= htmlspecialchars($bio ?? '') ?></textarea>

        <label for="contact_info">Contact Info:</label>
        <input type="text" name="contact_info" id="contact_info" value="<?= htmlspecialchars($contact_info ?? '') ?>">

        <label for="photo">Profile Photo:</label>
        <input type="file" name="photo" id="photo">

        <button type="submit">Save</button>
    </form>
</body>
</html>
