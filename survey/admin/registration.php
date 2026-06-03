<?php
require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/database.php';

$db = new database();
$pdo = $db->connect();

// Only superadmins can register new admins
if (!isset($_SESSION['admin_data']['admin_id']) || $_SESSION['admin_data']['admin_role'] !== 'superadmin') {
    die("Access denied. Only superadmins can create admins.");
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Trim inputs
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name'] ?? '');
    $username   = trim($_POST['username'] ?? '');
    $password   = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    $role = $_POST['role'] ?? '';

    // Validate inputs
    if (empty($first_name) || empty($username) || empty($password) || empty($confirm_password) || empty($role)) {
        $errors[] = "Input required fields.";
    }

    $valid_roles = ['admin', 'superadmin'];
    if (!in_array($role, $valid_roles)) {
        $errors[] = "Invalid role selected.";
    }

    // Check password match
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert admin safely with try-catch
        try {
            $stmt = $pdo->prepare("INSERT INTO admins (first_name, last_name, username, password, role) VALUES (:first_name, :last_name, :username, :password, :role)");
            $stmt->execute([
                ':first_name' => $first_name,
                ':last_name' => $last_name,
                ':username' => $username,
                ':password' => $hashed_password,
                ':role' => $role
            ]);

            $success = "Admin created successfully!";
        } catch (PDOException $e) {
            // Handle duplicate username
            if ($e->getCode() === '23000') {
                $errors[] = "Username already exists.";
            } else {
                $errors[] = "Database error: " . $e->getMessage();
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration - Nabia University Portal</title>
    <link rel="icon" href="/survey/admin/assets/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/survey/admin/assets/favicon.ico" type="image/x-icon">
    <link href="/survey/admin/assets/fonts/poppins/css/poppins.css" rel="stylesheet" >
    <link rel="stylesheet" href="/survey/assets/css/styles.css" />
</head>
<body>
    <!-- Background Orbs -->
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>

    <!-- Splash Screen -->
    <div class="splash-screen">
        <img src="/survey/admin/assets/images/SchoolLogo.png" alt="School Logo" class="splash-logo">
        <div class="splash-text">Nabia University</div>
        <div class="loader-line"></div>
    </div>

    <div class="main-wrapper">
        <!-- Header -->
        <header class="academic-header">
            <a href="/survey/admin/index.php">
                <img src="/survey/admin/assets/images/SchoolLogo.png" alt="School Logo" class="academic-logo">
                <div class="academic-text">
                    <span class="univ-name">Nabia University</span>
                    <span class="univ-sub">Value academic and passion</span>
                </div>
            </a>
        </header>

        <!-- Registration Card -->
        <main class="card">
            <h1>Create Your Account</h1>

            <form action="#" method="POST">
                <?php if ($errors): ?>
                    <div class="card">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <p><?php echo htmlspecialchars($success); ?></p>
                <?php endif; ?>

                <div class="form-row">
                    <div class="form-group">
                        <label for="fname">First Name</label>
                        <input type="text" id="fname" name="fname" placeholder="Enter your first name" required>
                    </div>

                    <div class="form-group">
                        <label for="lname">Last Name</label>
                        <input type="text" id="lname" name="lname" placeholder="Enter your last name">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Enter your username" required>
                    </div>

                    <div class="form-group">
                        <label for="role">Role</label>
                        <select id="role" name="role" required>
                            <option value="" disabled selected>Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="superadmin">Superadmin</option>
                        </select>
                    </div>
                </div>
                <div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Create a password" required>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
                    </div>
                </div>

              <!--   <div class="checkbox-group">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">I agree to the terms and conditions</label>
                </div> -->

                <div class="button-container">
                    <!-- <a href="login.html" class="btn-back">Already have an account? Login</a> -->
                    <a href="login.php">Already have an account? Login</a>
                    <button type="submit" class="btn-submit">Register</button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>
