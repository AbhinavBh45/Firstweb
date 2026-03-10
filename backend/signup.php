<?php
require_once 'config.php';

// Redirect if already logged in
if (is_logged_in()) {
    header("Location: dashboard.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    if (empty($full_name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters!";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Check if email already exists
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_stmt->store_result();
        
        if ($check_stmt->num_rows > 0) {
            $error = "Email already registered!";
        } else {
            // Hash password and insert user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $full_name, $email, $hashed_password);
            
            if ($stmt->execute()) {
                $success = "Account created successfully! Redirecting to login...";
                header("refresh:2;url=login.php");
            } else {
                $error = "Registration failed. Please try again.";
            }
            $stmt->close();
        }
        $check_stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CineBook - Sign Up</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    body {
      margin: 0;
      padding: 0;
      background: linear-gradient(to right, #000000, #1f1f1f);
      font-family: 'Arial', sans-serif;
      color: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }
    .form-container {
      background: rgba(255,255,255,0.08);
      padding: 40px;
      border-radius: 12px;
      width: 100%;
      max-width: 400px;
      text-align: center;
      box-shadow: 0 8px 25px rgba(0,0,0,0.7);
      animation: fadeIn 1s ease-out;
    }
    .form-container h2 {
      color: #ef4444;
      margin-bottom: 20px;
    }
    input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border-radius: 6px;
      border: 1px solid rgba(255,255,255,0.3);
      background: rgba(255,255,255,0.1);
      color: #fff;
      transition: border 0.3s;
      box-sizing: border-box;
    }
    input:focus {
      border-color: #ef4444;
      outline: none;
    }
    input::placeholder {
      color: rgba(255,255,255,0.6);
    }
    .btn {
      background: linear-gradient(45deg, #ef4444, #7f1d1d);
      border: none;
      padding: 12px;
      color: #fff;
      font-size: 16px;
      border-radius: 6px;
      cursor: pointer;
      width: 100%;
      margin-top: 12px;
      transition: transform 0.3s ease;
    }
    .btn:hover {
      transform: scale(1.05);
    }
    .back-home {
      display: inline-block;
      margin-top: 16px;
      color: #f87171;
      text-decoration: none;
      font-weight: bold;
      transition: color 0.3s;
    }
    .back-home:hover {
      color: #fff;
    }
    .alert {
      padding: 12px;
      margin-bottom: 16px;
      border-radius: 6px;
      text-align: center;
    }
    .alert-error {
      background: rgba(239, 68, 68, 0.2);
      border: 1px solid #ef4444;
      color: #fca5a5;
    }
    .alert-success {
      background: rgba(16, 185, 129, 0.2);
      border: 1px solid #10b981;
      color: #6ee7b7;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2><i class="fas fa-user-plus"></i> Sign Up</h2>
    
    <?php if ($error): ?>
      <div class="alert alert-error"><?php echo h($error); ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
      <div class="alert alert-success"><?php echo h($success); ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
      <input type="text" name="full_name" placeholder="Full Name" required value="<?php echo isset($_POST['full_name']) ? h($_POST['full_name']) : ''; ?>">
      <input type="email" name="email" placeholder="Email" required value="<?php echo isset($_POST['email']) ? h($_POST['email']) : ''; ?>">
      <input type="password" name="password" placeholder="Password (min 6 characters)" required>
      <input type="password" name="confirm_password" placeholder="Confirm Password" required>
      <button type="submit" class="btn">Sign Up</button>
    </form>
    <p style="margin-top: 16px; font-size: 14px;">Already have an account? <a href="login.php" style="color: #f87171;">Login here</a></p>
    <a href="index.php" class="back-home"><i class="fas fa-home"></i> Back to Home</a>
  </div>
</body>
</html>