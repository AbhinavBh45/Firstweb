<?php
require_once 'config.php';

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $message = trim($_POST['message']);
    
    if (empty($email) || empty($phone) || empty($message)) {
        $error = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } elseif (!preg_match('/^[0-9]{10,15}$/', $phone)) {
        $error = "Invalid phone number format!";
    } else {
        $stmt = $conn->prepare("INSERT INTO feedback (email, phone, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $phone, $message);
        
        if ($stmt->execute()) {
            $success = true;
        } else {
            $error = "Failed to submit feedback. Please try again.";
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
    <title>CineBook - Feedback</title>
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
        .message-container {
            background: rgba(255,255,255,0.08);
            padding: 40px;
            border-radius: 12px;
            width: 100%;
            max-width: 500px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0,0,0,0.7);
            animation: fadeIn 1s ease-out;
        }
        .success-icon {
            font-size: 64px;
            color: #10b981;
            margin-bottom: 20px;
        }
        .error-icon {
            font-size: 64px;
            color: #ef4444;
            margin-bottom: 20px;
        }
        h2 {
            color: #f87171;
            margin-bottom: 16px;
        }
        p {
            color: #deb2a6;
            margin-bottom: 24px;
            font-size: 18px;
        }
        .btn {
            background: linear-gradient(45deg, #ef4444, #7f1d1d);
            border: none;
            padding: 12px 24px;
            color: #fff;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: transform 0.3s ease;
        }
        .btn:hover {
            transform: scale(1.05);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="message-container">
        <?php if ($success): ?>
            <i class="fas fa-check-circle success-icon"></i>
            <h2>Thank You!</h2>
            <p>Your feedback has been submitted successfully. We appreciate your input!</p>
        <?php else: ?>
            <i class="fas fa-exclamation-circle error-icon"></i>
            <h2>Oops!</h2>
            <p><?php echo h($error); ?></p>
        <?php endif; ?>
        <a href="index.php" class="btn"><i class="fas fa-home"></i> Back to Home</a>
    </div>
    
    <?php if ($success): ?>
    <script>
        setTimeout(function() {
            window.location.href = 'index.php';
        }, 3000);
    </script>
    <?php endif; ?>
</body>
</html>