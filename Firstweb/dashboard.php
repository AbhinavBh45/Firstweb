<?php
require_once 'config.php';
require_login();

$user_name = $_SESSION['user_name'];
$user_id = $_SESSION['user_id'];

// Fetch user's bookings
$stmt = $conn->prepare("SELECT * FROM bookings WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$bookings = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineBook - Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style1.css">
    <style>
        .dashboard-header {
            background: linear-gradient(135deg, #000000, #1f1f1f);
            padding: 24px 0;
            margin-bottom: 32px;
        }
        .welcome-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }
        .welcome-text h2 {
            color: #ef4444;
            margin: 0;
        }
        .dashboard-nav {
            display: flex;
            gap: 12px;
        }
        .bookings-section {
            padding: 32px 0;
        }
        .booking-card {
            background: rgba(255,255,255,0.08);
            padding: 24px;
            border-radius: 12px;
            margin-bottom: 16px;
            border: 1px solid rgba(255,255,255,0.2);
            transition: transform 0.3s ease;
        }
        .booking-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(239, 68, 68, 0.3);
        }
        .booking-card h4 {
            color: #ef4444;
            margin-bottom: 12px;
        }
        .booking-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
            margin-top: 12px;
        }
        .info-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .info-item i {
            color: #f87171;
        }
        .no-bookings {
            text-align: center;
            padding: 64px 20px;
            background: rgba(255,255,255,0.05);
            border-radius: 12px;
        }
        .no-bookings i {
            font-size: 64px;
            color: #ef4444;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container header-container">
            <div class="logo">
                <i class="fas fa-film"></i>
                <h1>CineBook</h1>
            </div>
            <nav>
                <a href="dashboard.php" class="btn-link" style="color: #ef4444;">Dashboard</a>
                <a href="index.php" class="btn-link">Browse Movies</a>
                <a href="logout.php" class="btn-signup">Logout</a>
            </nav>
        </div>
    </header>

    <!-- Dashboard Header -->
    <section class="dashboard-header">
        <div class="container welcome-section">
            <div class="welcome-text">
                <h2><i class="fas fa-user-circle"></i> Welcome, <?php echo h($user_name); ?>!</h2>
                <p style="color: #deb2a6; margin-top: 8px;">Manage your bookings and explore more movies</p>
            </div>
            <div class="dashboard-nav">
                <a href="booking.php" class="btn-book" style="display: inline-block; text-decoration: none; padding: 12px 24px;">
                    <i class="fas fa-ticket-alt"></i> Book New Ticket
                </a>
            </div>
        </div>
    </section>

    <!-- Bookings Section -->
    <section class="bookings-section">
        <div class="container">
            <h3 style="font-size: 30px; font-weight: bold; margin-bottom: 32px; color: #f87171;">
                <i class="fas fa-list"></i> Your Bookings
            </h3>
            
            <?php if ($bookings->num_rows > 0): ?>
                <?php while($booking = $bookings->fetch_assoc()): ?>
                    <div class="booking-card">
                        <h4><i class="fas fa-film"></i> <?php echo h($booking['movie_title']); ?></h4>
                        <div class="booking-info">
                            <div class="info-item">
                                <i class="fas fa-calendar"></i>
                                <span><?php echo date('M d, Y', strtotime($booking['booking_date'])); ?></span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-clock"></i>
                                <span><?php echo h($booking['showtime']); ?></span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-chair"></i>
                                <span><?php echo $booking['seats']; ?> Seat(s)</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-rupee-sign"></i>
                                <span>₹<?php echo number_format($booking['total_price'], 2); ?></span>
                            </div>
                        </div>
                        <p style="margin-top: 12px; font-size: 12px; color: #9ca3af;">
                            Booked on: <?php echo date('M d, Y g:i A', strtotime($booking['created_at'])); ?>
                        </p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-bookings">
                    <i class="fas fa-ticket-alt"></i>
                    <h3 style="color: #f87171;">No Bookings Yet</h3>
                    <p style="color: #deb2a6; margin: 16px 0;">Start exploring movies and book your first ticket!</p>
                    <a href="booking.php" class="btn-book" style="display: inline-block; text-decoration: none; max-width: 200px; margin: 0 auto;">
                        Book Now
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2023 CineBook. All rights reserved. Powered by passion for cinema.</p>
        </div>
    </footer>
</body>
</html>