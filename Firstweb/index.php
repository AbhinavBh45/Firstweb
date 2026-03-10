<?php
require_once 'config.php';

// Fetch movies from database
$movies_query = "SELECT * FROM movies ORDER BY title";
$movies_result = $conn->query($movies_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineBook - Online Movie Ticket Booking</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style1.css">
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
                <?php if (is_logged_in()): ?>
                    <a href="dashboard.php" class="btn-link">Dashboard</a>
                    <a href="booking.php" class="btn-link">Book Tickets</a>
                    <a href="logout.php" class="btn-signup">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn-link">Sign In</a>
                    <a href="signup.php" class="btn-signup">Sign Up</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h2>Book Your Movie Tickets Today</h2>
            <p>Experience the magic of cinema at your fingertips. Choose from the latest blockbusters and book your seats now!</p>
            <div class="location-selector">
                <label for="location">Select Your Location:</label>
                <select id="location">
                    <option value="">Choose a city...</option>
                    <option value="varanasi">Varanasi</option>
                    <option value="kanpur">Kanpur</option>
                    <option value="patna">Patna</option>
                    <option value="chennai">Chennai</option>
                    <option value="jaipur">Jaipur</option>
                    <option value="gangtok">Gangtok</option>
                </select>
            </div>
        </div>
    </section>

    <!-- Movies Section -->
    <section class="movies-section">
        <div class="container">
            <h3>Latest Movie Releases</h3>
            <div class="movie-grid">
                <?php 
                if ($movies_result && $movies_result->num_rows > 0):
                    while($movie = $movies_result->fetch_assoc()): 
                ?>
                    <div class="movie-card">
                        <img src="<?php echo h($movie['poster_image']); ?>" alt="<?php echo h($movie['title']); ?>" class="poster-img">
                        <h4><?php echo h($movie['title']); ?></h4>
                        <?php if (is_logged_in()): ?>
                            <a href="booking.php?movie_id=<?php echo $movie['id']; ?>" class="btn-book" style="text-decoration: none;">Book Now</a>
                        <?php else: ?>
                            <a href="login.php" class="btn-book" style="text-decoration: none;">Book Now</a>
                        <?php endif; ?>
                    </div>
                <?php 
                    endwhile;
                else:
                    // Fallback to static movies if database is empty
                    $static_movies = [
                        ['title' => 'The Conjuring: Last Rites', 'image' => 'TheConjuring.webp'],
                        ['title' => 'Baaghi 4', 'image' => 'Baaghi4.webp'],
                        ['title' => 'Multiverse of Madness', 'image' => 'Multiverse_of_Madness.webp'],
                        ['title' => 'Coolie', 'image' => 'coolie.webp'],
                        ['title' => 'Jolly LLB 3', 'image' => 'Jolly-LLB-3.webp'],
                        ['title' => 'Mirai', 'image' => 'mirai-poster.webp'],
                        ['title' => 'Ne Zha 2', 'image' => 'Ne_Zha_2.webp'],
                        ['title' => 'Thama', 'image' => 'Thama.webp']
                    ];
                    foreach ($static_movies as $movie):
                ?>
                    <div class="movie-card">
                        <img src="<?php echo h($movie['image']); ?>" alt="<?php echo h($movie['title']); ?>" class="poster-img">
                        <h4><?php echo h($movie['title']); ?></h4>
                        <a href="login.php" class="btn-book" style="text-decoration: none;">Book Now</a>
                    </div>
                <?php 
                    endforeach;
                endif; 
                ?>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section">
        <div class="container">
            <h3>About CineBook</h3>
            <p>CineBook is your ultimate destination for hassle-free movie ticket booking. With our user-friendly platform, you can explore the latest releases, select your preferred showtimes, and secure your seats in minutes. We partner with the best cinemas across the country to bring you an unparalleled cinematic experience. Join millions of movie lovers and make every movie night unforgettable!</p>
        </div>
    </section>

    <!-- Contact and Feedback Section -->
    <section class="contact-feedback-section">
        <div class="container">
            <div class="contact-feedback-grid">
                <div class="contact-card">
                    <h3>Contact Us</h3>
                    <p><i class="fas fa-phone" style="color: #10b981; margin-right: 8px;"></i>Phone: 6738369244</p>
                    <p><i class="fas fa-envelope" style="color: #3b82f6; margin-right: 8px;"></i>Email: abhinav2371@gmail.com</p>
                    <p>We'd love to hear from you! Reach out for any inquiries or support.</p>
                </div>
                <div class="feedback-card">
                    <h3>Leave Your Feedback</h3>
                    <form id="feedbackForm" class="feedback-form" action="feedback.php" method="POST">
                        <input type="email" name="email" placeholder="Your Email" required>
                        <input type="tel" name="phone" placeholder="Your Phone Number" required>
                        <textarea name="message" placeholder="Your Feedback..." rows="4" required></textarea>
                        <button type="submit" class="btn-book btn-submit">Submit Feedback</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2023 CineBook. All rights reserved. Powered by passion for cinema.</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>