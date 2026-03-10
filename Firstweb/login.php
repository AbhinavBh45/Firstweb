<?php
require_once 'config.php';

// Redirect if already logged in
if (is_logged_in()) {
    header("Location: dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = "All fields are required!";
    } else {
        $stmt = $conn->prepare("SELECT id, full_name, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                // Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_email'] = $email;
                
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid email or password!";
            }
        } else {
            $error = "Invalid email or password!";
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
  <title>CineBook - Login</title>
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
      background: rgba(239, 68, 68, 0.2);
      border: 1px solid #ef4444;
      color: #fca5a5;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2><i class="fas fa-sign-in-alt"></i> Login</h2>
    
    <?php if ($error): ?>
      <div class="alert"><?php echo h($error); ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
      <input type="email" name="email" placeholder="Email" required value="<?php echo isset($_POST['email']) ? h($_POST['email']) : ''; ?>">
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" class="btn">Sign In</button>
    </form>
    <p style="margin-top: 16px; font-size: 14px;">Don't have an account? <a href="signup.php" style="color: #f87171;">Sign up here</a></p>
    <a href="index.php" class="back-home"><i class="fas fa-home"></i> Back to Home</a>
  </div>
</body>
</html>