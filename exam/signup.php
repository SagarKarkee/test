<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $firstname = $_POST['firstname'];
    $secondname = $_POST['secondname'];
    $phone = $_POST['phone'];

    // Handling profile photo upload
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true); // Create folder if it doesn't exist
    }

    $profile_photo = null; // Default to null if no file is uploaded
    if (!empty($_FILES['profile_photo']['name'])) {
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        $file_ext = pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION);
        $file_size = $_FILES['profile_photo']['size'];

        if (!in_array(strtolower($file_ext), $allowed_ext)) {
            die("Error: Invalid file type. Only JPG, PNG, and GIF are allowed.");
        }

        if ($file_size > 2 * 1024 * 1024) { // 2MB limit
            die("Error: File size too large. Max 2MB allowed.");
        }

        // Generate unique filename
        $new_filename = time() . "_" . basename($_FILES['profile_photo']['name']);
        $profile_photo = $upload_dir . $new_filename;

        if (!move_uploaded_file($_FILES['profile_photo']['tmp_name'], $profile_photo)) {
            die("Error: Failed to upload file.");
        }
    }

    // Insert into database
    $sql = "INSERT INTO users (username, email, password, firstname, secondname, phone, profile_photo) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username, $email, $password, $firstname, $secondname, $phone, $profile_photo]);

    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Signup</h1>
        <form action="signup.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="firstname" placeholder="First Name" required>
            <input type="text" name="secondname" placeholder="Second Name" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <input type="file" name="profile_photo" accept="image/*">
            <button type="submit">Signup</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </div>
</body>
</html>
