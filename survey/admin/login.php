<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/database.php';


$db = new database();
$pdo = $db->connect();

$errors = [];

// Check for a remembered user via cookie
if (!isset($_SESSION['admin_data']['admin_id']) && isset($_COOKIE['remember_me_token'])) {
    $token = $_COOKIE['remember_me_token'];
    try {
        $stmt = $pdo->prepare("SELECT id, role, username, first_name, last_name FROM admins WHERE remember_token = :token");
        $stmt->execute([':token' => $token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            session_regenerate_id(true);
            $_SESSION['admin_data']['admin_id']   = $user['id'];
            $_SESSION['admin_data']['role']       = $user['role'];
            $_SESSION['admin_data']['username']   = $user['username'];
            $_SESSION['admin_data']['first_name'] = $user['first_name'] ?? '';
            $_SESSION['admin_data']['last_name']  = $user['last_name'] ?? '';

            header("Location: /survey/admin/index.php");
            exit;
        }
    } catch (PDOException $e) {
        $errors[] = "Database error: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $remember = isset($_POST['remember']);

    if (empty($username) || empty($password)) {
        $errors[] = "Both username and password are required.";
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT id, role, username, first_name, last_name, password FROM admins WHERE username = :username");
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['admin_data']['admin_id']   = $user['id'];
                $_SESSION['admin_data']['role']       = $user['role'];
                $_SESSION['admin_data']['username']   = $user['username'];
                $_SESSION['admin_data']['first_name'] = $user['first_name'] ?? '';
                $_SESSION['admin_data']['last_name']  = $user['last_name'] ?? '';


                if ($remember) {
                    // Generate secure token
                    $token = bin2hex(random_bytes(32));
                    setcookie('remember_me_token', $token, time() + (86400 * 30), "/", "", true, true); // 30 days, secure, httponly
                    $update = $pdo->prepare("UPDATE admins SET remember_token = :token WHERE id = :id");
                    $update->execute([':token' => $token, ':id' => $user['id']]);
                } else {
                    // Clear token if not remembering
                    setcookie('remember_me_token', '', time() - 3600, "/", "", true, true);
                    $update = $pdo->prepare("UPDATE admins SET remember_token = NULL WHERE id = :id");
                    $update->execute([':id' => $user['id']]);
                }

                $_SESSION['flash_success'] = 'Logged in successfully.';
                header("Location: /survey/admin/index.php");
                exit;
            } else {
                $errors[] = "Invalid username or password.";
            }
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Nabia University Portal</title>
    <link rel="icon" href="/survey/admin/assets/images/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/survey/admin/assets/images/favicon.ico" type="image/x-icon">
    <link href="/survey/admin/assets/fonts/poppins/css/poppins.css" rel="stylesheet" >
    <link rel="stylesheet" href="/survey/assets/css/styles.css" />
</head>
<body>
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>

    <div class="splash-screen">
        <img src="/survey/admin/assets/images/SchoolLogo.png" alt="School Logo" class="splash-logo">
        <div class="splash-text">Nabia University</div>
        <div class="loader-line"></div>
    </div>

    <div class="main-wrapper">
        <header class="academic-header">
            <img src="/survey/admin/assets/images/SchoolLogo.png" alt="School Logo" class="academic-logo">
            <div class="academic-text">
                <span class="univ-name">Nabia University</span>
                <span class="univ-sub">Value academic and passion</span>
            </div>
        </header>

        <main class="card">
            <h1>Login to Your Account</h1>

            <?php if (!empty($errors)): ?>
                <div class="card flash-card error"><ul><?php foreach($errors as $er): ?><li><p><?= e($er) ?></p></li><?php endforeach; ?></ul></div>
                <?php unset($errors); ?>
            <?php endif; ?>
            <?php // debug(); ?>


            <form action="#" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Enter your username" required value="<?php echo htmlspecialchars($username ?? ''); ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <div class="checkbox-group" style="justify-content: space-between;">
                            <label for="remember">
                                <input type="checkbox" id="remember" name="remember" <?php echo !empty($remember) ? 'checked' : ''; ?>>
                                Remember me
                            </label>

                            <a href="#" style="text-align: right;">Forgot password?</a>
                        </div>
                    </div>
                </div>

                <div class="footer-actions">
                    <a href="registration.php">Create Account</a>
                    <button type="submit" class="btn-submit">Login</button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>