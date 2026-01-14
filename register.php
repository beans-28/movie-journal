<?php
require_once 'config.php';
require_once 'auth.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header("Location: index.php");
    exit();
}

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // Validation
    if (empty($username)) {
        $errorMessage = "Username is required.";
    } elseif (strlen($username) < 3) {
        $errorMessage = "Username must be at least 3 characters.";
    } elseif (empty($email)) {
        $errorMessage = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Invalid email format.";
    } elseif (empty($password)) {
        $errorMessage = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errorMessage = "Password must be at least 6 characters.";
    } elseif ($password !== $confirmPassword) {
        $errorMessage = "Passwords do not match.";
    } else {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $errorMessage = "Username already taken.";
        } else {
            // Check if email already exists
            $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $errorMessage = "Email already registered.";
            } else {
                // Hash password and create user
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                
                $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $email, $hashedPassword);
                
                if ($stmt->execute()) {
                    $successMessage = "Registration successful! You can now log in.";
                    $username = $email = '';
                } else {
                    $errorMessage = "Registration failed. Please try again.";
                }
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Movie Journal</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php"><i class="bi bi-film"></i> MOVIE JOURNAL</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">LOGIN</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="register.php">REGISTER</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <div class="container mt-5 pt-3">
        <div class="row justify-content-center">
            <div class="col-md-6">
                
                <h1 class="text-center mb-2">CREATE ACCOUNT</h1>
                <p class="text-center mb-5" style="color: #808080; font-style: italic;">Join the cinema community</p>
                
                <!-- SUCCESS ALERT -->
                <?php if (!empty($successMessage)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong><i class="bi bi-check-circle"></i> Success!</strong> <?php echo $successMessage; ?>
                    <a href="login.php" class="alert-link">Log in now</a>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <!-- ERROR ALERT -->
                <?php if (!empty($errorMessage)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><i class="bi bi-exclamation-triangle"></i> Error!</strong> <?php echo $errorMessage; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <!-- REGISTRATION FORM -->
                <div class="card shadow-lg">
                    <div class="card-body p-4">
                        
                        <form method="POST" action="register.php">
                            
                            <!-- Username -->
                            <div class="mb-3">
                                <label for="username" class="form-label">USERNAME</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?php echo htmlspecialchars($username ?? ''); ?>" 
                                       placeholder="Choose a username" required>
                                <div class="form-text">At least 3 characters</div>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">EMAIL</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($email ?? ''); ?>" 
                                       placeholder="your@email.com" required>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">PASSWORD</label>
                                <input type="password" class="form-control" id="password" name="password" 
                                       placeholder="Create a password" required>
                                <div class="form-text">At least 6 characters</div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-4">
                                <label for="confirm_password" class="form-label">CONFIRM PASSWORD</label>
                                <input type="password" class="form-control" id="confirm_password" 
                                       name="confirm_password" placeholder="Repeat your password" required>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-person-plus"></i> CREATE ACCOUNT
                                </button>
                            </div>

                        </form>

                        <hr class="my-4" style="border-color: rgba(212, 175, 55, 0.3);">
                        
                        <p class="text-center mb-0" style="color: #808080;">
                            Already have an account? 
                            <a href="login.php" style="color: #d4af37;">Log in here</a>
                        </p>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="text-white text-center py-4 mt-5">
        <p class="mb-0" style="color: #808080;">© 2025 Movie Journal • Group 10 - Final Project</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$conn->close();
?>