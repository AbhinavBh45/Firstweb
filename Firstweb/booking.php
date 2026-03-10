<?php
require_once 'config.php';
require_login();

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Function to generate unique booking reference
function generateBookingReference($conn) {
    do {
        $reference = 'BK' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        $stmt = $conn->prepare("SELECT id FROM bookings WHERE booking_reference = ?");
        $stmt->bind_param("s", $reference);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
    } while ($result->num_rows > 0);
    
    return $reference;
}

// Handle booking submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $movie_id = intval($_POST['movie_id']);
    $movie_title = trim($_POST['movie_title']);
    $seats = intval($_POST['seats']);
    $showtime = trim($_POST['showtime']);
    $booking_date = $_POST['booking_date'];
    $price_per_seat = 250; // ₹250 per seat
    $total_price = $seats * $price_per_seat;
    
    if ($seats < 1 || $seats > 10) {
        $error = "Please select between 1 and 10 seats.";
    } elseif (empty($showtime) || empty($booking_date)) {
        $error = "Please select showtime and date.";
    } elseif (strtotime($booking_date) < strtotime(date('Y-m-d'))) {
        $error = "Please select a future date.";
    } else {
        // Generate unique booking reference
        $booking_reference = generateBookingReference($conn);
        
        $stmt = $conn->prepare("INSERT INTO bookings (booking_reference, user_id, movie_id, movie_title, seats, showtime, booking_date, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siisissd", $booking_reference, $user_id, $movie_id, $movie_title, $seats, $showtime, $booking_date, $total_price);
        
        if ($stmt->execute()) {
            $success = "Booking successful! Booking Reference: " . $booking_reference . " | Total: ₹" . number_format($total_price, 2);
        } else {
            $error = "Booking failed. Please try again.";
        }
        $stmt->close();
    }
}

// Fetch available movies
$movies_query = "SELECT * FROM movies ORDER BY title";
$movies_result = $conn->query($movies_query);

// Get pre-selected movie if movie_id is in URL
$selected_movie_id = isset($_GET['movie_id']) ? intval($_GET['movie_id']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineBook - Book Tickets</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style1.css">
    <style>
        .booking-container {
            max-width: 600px;
            margin: 64px auto;
            padding: 40px;
            background: rgba(255,255,255,0.08);
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.7);
        }
        .booking-container h2 {
            color: #ef4444;
            text-align: center;
            margin-bottom: 32px;
        }
        .form-group {
            margin-bottom: 24px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #f87171;
            font-weight: bold;
        }
        .form-group select,
        .form-group input {
            width: 100%;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid rgba(255,255,255,0.3);
            background: rgba(255,255,255,0.1);
            color: #fff;
            font-size: 16px;
            box-sizing: border-box;
        }
        .form-group select:focus,
        .form-group input:focus {
            border-color: #ef4444;
            outline: none;
        }
        .form-group select option {
            background: #1f1f1f;
            color: #fff;
        }
        .price-info {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid #10b981;
            padding: 16px;
            border-radius: 8px;
            text-align: center;
            margin: 24px 0;
        }
        .price-info h3 {
            color: #10b981;
            margin: 0;
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
                <a href="dashboard.php" class="btn-link">Dashboard</a>
                <a href="index.php" class="btn-link">Home</a>
                <a href="logout.php" class="btn-signup">Logout</a>
            </nav>
        </div>
    </header>

    <!-- Booking Form -->
    <div class="container">
        <div class="booking-container">
            <h2><i class="fas fa-ticket-alt"></i> Book Your Movie Tickets</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo h($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php echo h($success); ?>
                    <br><a href="dashboard.php" style="color: #6ee7b7; text-decoration: underline;">View My Bookings</a>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" id="bookingForm">
                <div class="form-group">
                    <label><i class="fas fa-film"></i> Select Movie</label>
                    <select name="movie_id" id="movieSelect" required>
                        <option value="">Choose a movie...</option>
                        <?php while($movie = $movies_result->fetch_assoc()): ?>
                            <option value="<?php echo $movie['id']; ?>" 
                                    data-title="<?php echo h($movie['title']); ?>"
                                    <?php echo ($selected_movie_id == $movie['id']) ? 'selected' : ''; ?>>
                                <?php echo h($movie['title']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <input type="hidden" name="movie_title" id="movieTitle">
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-calendar"></i> Select Date</label>
                    <input type="date" name="booking_date" required min="<?php echo date('Y-m-d'); ?>">
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-clock"></i> Select Showtime</label>
                    <select name="showtime" required>
                        <option value="">Choose showtime...</option>
                        <option value="10:00 AM">10:00 AM</option>
                        <option value="01:00 PM">01:00 PM</option>
                        <option value="04:00 PM">04:00 PM</option>
                        <option value="07:00 PM">07:00 PM</option>
                        <option value="10:00 PM">10:00 PM</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-chair"></i> Number of Seats</label>
                    <input type="number" name="seats" id="seatsInput" min="1" max="10" value="1" required>
                </div>
                
                <div class="price-info">
                    <p style="margin: 0 0 8px 0; color: #deb2a6;">Price per seat: ₹250</p>
                    <h3><i class="fas fa-rupee-sign"></i> Total: ₹<span id="totalPrice">250</span></h3>
                </div>
                
                <button type="submit" class="btn-book">
                    <i class="fas fa-check-circle"></i> Confirm Booking
                </button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2023 CineBook. All rights reserved. Powered by passion for cinema.</p>
        </div>
    </footer>

    <script>
        // Update movie title hidden field on page load
        window.addEventListener('DOMContentLoaded', function() {
            const movieSelect = document.getElementById('movieSelect');
            const selectedOption = movieSelect.options[movieSelect.selectedIndex];
            if (selectedOption.value) {
                document.getElementById('movieTitle').value = selectedOption.getAttribute('data-title') || '';
            }
        });

        // Update movie title hidden field on change
        document.getElementById('movieSelect').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('movieTitle').value = selectedOption.getAttribute('data-title') || '';
        });

        // Calculate total price
        document.getElementById('seatsInput').addEventListener('input', function() {
            const seats = parseInt(this.value) || 1;
            const pricePerSeat = 250;
            const total = seats * pricePerSeat;
            document.getElementById('totalPrice').textContent = total;
        });
    </script>
</body>
</html>