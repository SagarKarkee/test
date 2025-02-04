<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $firstname = $_POST['firstname'];
    $secondname = $_POST['secondname'];
    $phone = $_POST['phone'];
    
    // Handle profile photo upload
    if (!empty($_FILES['profile_photo']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_photo"]["name"]);
        
        if (move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_file)) {
            $profile_photo = $target_file;
        } else {
            $profile_photo = NULL;
        }
    } else {
        $profile_photo = NULL;
    }

    // Update query
    if ($profile_photo) {
        $sql = "UPDATE users SET username = ?, email = ?, firstname = ?, secondname = ?, phone = ?, profile_photo = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username, $email, $firstname, $secondname, $phone, $profile_photo, $user_id]);
    } else {
        $sql = "UPDATE users SET username = ?, email = ?, firstname = ?, secondname = ?, phone = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username, $email, $firstname, $secondname, $phone, $user_id]);
    }
    
    header("Location: profile.php");
    exit();
}

$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Update Profile</h1>
        <form action="update_profile.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            <input type="text" name="firstname" value="<?php echo htmlspecialchars($user['firstname']); ?>" required>
            <input type="text" name="secondname" value="<?php echo htmlspecialchars($user['secondname']); ?>" required>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
            <input type="file" name="profile_photo" accept="image/*">
            <button type="submit">Update</button>
        </form>
        <br>
        <a href="profile.php">Back to Profile</a>
    </div>
</body>
</html>
