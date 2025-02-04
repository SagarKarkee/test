<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT username, email, firstname, secondname, phone, profile_photo FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fa;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh; 
        }
        .navbar {
            background-color:rgb(44, 80, 66);
            padding: 10px 20px;
            display: flex;
            justify-content: space-between; 
            align-items: center;
            width: 100%;
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        .nav-links li {
            display: inline;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            padding: 10px 15px;
        }

        .nav-links a:hover {
            background-color: #3498db;
            border-radius: 5px;
        }

        /* Logo styling */
        .logo img {
            height: 60px; 
        }
        .container {
            font-family: Arial, sans-serif;
            width: 50%;
            margin: 100px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            flex: 1;
            padding:20px;
        }
        
        .profile-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
        .btn:hover {
            background: #0056b3;
        }
        .delete-btn {
            display: inline-block;
            padding: 10px 20px;
            background: red;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
        .delete-btn:hover {
            background: darkred;
        }
        footer {
            padding: 10px;
            position: fixed;
            width: 100%;
            bottom: 0;
            /* left: 0; */
            background-color:rgb(44, 80, 66);
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 14px;
        
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <img src="./image/kishani logo.png" alt="Website Logo" />
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="profile.php">View Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="signup.php">Signup</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    
    <div class="container">
        <h1>Profile</h1>
        <img src="<?php echo htmlspecialchars($user['profile_photo'] ?? 'uploads/default.png'); ?>" alt="Profile Photo" class="profile-photo">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($user['firstname'] . ' ' . $user['secondname']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Phone No.:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
        <a href="update_profile.php" class="btn">Update</a>
        <a href="delete_account.php" class="delete-btn" onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">Delete Account</a>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
