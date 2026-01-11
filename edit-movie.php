<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';
require_once 'auth.php';

// Require login
requireLogin();

$successMessage = '';
$errorMessage = '';
$movie = null;
$userId = getCurrentUserId();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$movieId = intval($_GET['id']);

// Verify movie belongs to current user
$stmt = $conn->prepare("SELECT * FROM movies WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $movieId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: index.php");
    exit();
}

$movie = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = isset($_POST['movieTitle']) ? trim($_POST['movieTitle']) : '';
    $genre = isset($_POST['movieGenre']) ? trim($_POST['movieGenre']) : '';
    $rating = isset($_POST['movieRating']) ? intval($_POST['movieRating']) : 0;
    $dateWatched = isset($_POST['dateWatched']) ? $_POST['dateWatched'] : '';
    $review = isset($_POST['movieReview']) ? trim($_POST['movieReview']) : '';
    $posterUrl = isset($_POST['posterURL']) ? trim($_POST['posterURL']) : '';
    
    if (empty($title)) {
        $errorMessage = "Movie title is required.";
    } elseif (empty($genre)) {
        $errorMessage = "Genre is required.";
    } elseif (empty($rating) || $rating < 1 || $rating > 5) {
        $errorMessage = "Please select a valid rating (1-5).";
    } elseif (empty($dateWatched)) {
        $errorMessage = "Date watched is required.";
    } elseif (empty($review)) {
        $errorMessage = "Review is required.";
    } else {
        // Update only if movie belongs to user
        $stmt = $conn->prepare("UPDATE movies SET title=?, genre=?, rating=?, date_watched=?, review=?, poster_url=? WHERE id=? AND user_id=?");
        
        if ($stmt === false) {
            $errorMessage = "Error preparing statement: " . $conn->error;
        } else {
            $stmt->bind_param("ssisssii", $title, $genre, $rating, $dateWatched, $review, $posterUrl, $movieId, $userId);
            
            if ($stmt->execute()) {
                $successMessage = "Movie '$title' updated successfully!";
                // Refresh movie data
                $movie['title'] = $title;
                $movie['genre'] = $genre;
                $movie['rating'] = $rating;
                $movie['date_watched'] = $dateWatched;
                $movie['review'] = $review;
                $movie['poster_url'] = $posterUrl;
            } else {
                $errorMessage = "Error updating movie: " . $stmt->error;
            }
            
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Movie - Movie Journal</title>
    
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
                        <a class="nav-link" href="index.php">HOME</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="add-movie.php">ADD MOVIE</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="top-rated.php">TOP RATED</a>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link" style="color: #d4af37;">
                            <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars(getCurrentUsername()); ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="bi bi-box-arrow-right"></i> LOGOUT
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <div class="container mt-5 pt-3">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <h1 class="text-center mb-2">EDIT MOVIE</h1>
                <p class="text-center mb-5" style="color: #808080; font-style: italic;">Update your review and details</p>
                
                <!-- SUCCESS ALERT -->
                <?php if (!empty($successMessage)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>✓ Success!</strong> <?php echo $successMessage; ?>
                    <a href="index.php" class="alert-link">View all movies</a>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <!-- ERROR ALERT -->
                <?php if (!empty($errorMessage)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>✗ Error!</strong> <?php echo $errorMessage; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <!-- THE FORM -->
                <div class="card shadow-lg">
                    <div class="card-body p-4">
                        
                        <form method="POST" action="edit-movie.php?id=<?php echo $movieId; ?>">
                            
                            <!-- Movie Title -->
                            <div class="mb-3">
                                <label for="movieTitle" class="form-label">MOVIE TITLE</label>
                                <input type="text" class="form-control" id="movieTitle" name="movieTitle" 
                                       value="<?php echo htmlspecialchars($movie['title']); ?>" required>
                            </div>

                            <!-- Genre Dropdown -->
                            <div class="mb-3">
                                <label for="movieGenre" class="form-label">GENRE</label>
                                <select class="form-select" id="movieGenre" name="movieGenre" required>
                                    <option value="">Choose a genre...</option>
                                    <option value="Action" <?php echo ($movie['genre'] == 'Action') ? 'selected' : ''; ?>>Action</option>
                                    <option value="Comedy" <?php echo ($movie['genre'] == 'Comedy') ? 'selected' : ''; ?>>Comedy</option>
                                    <option value="Drama" <?php echo ($movie['genre'] == 'Drama') ? 'selected' : ''; ?>>Drama</option>
                                    <option value="Horror" <?php echo ($movie['genre'] == 'Horror') ? 'selected' : ''; ?>>Horror</option>
                                    <option value="Sci-Fi" <?php echo ($movie['genre'] == 'Sci-Fi') ? 'selected' : ''; ?>>Sci-Fi</option>
                                    <option value="Romance" <?php echo ($movie['genre'] == 'Romance') ? 'selected' : ''; ?>>Romance</option>
                                    <option value="Thriller" <?php echo ($movie['genre'] == 'Thriller') ? 'selected' : ''; ?>>Thriller</option>
                                    <option value="Animation" <?php echo ($movie['genre'] == 'Animation') ? 'selected' : ''; ?>>Animation</option>
                                    <option value="Documentary" <?php echo ($movie['genre'] == 'Documentary') ? 'selected' : ''; ?>>Documentary</option>
                                    <option value="Crime" <?php echo ($movie['genre'] == 'Crime') ? 'selected' : ''; ?>>Crime</option>
                                </select>
                            </div>

                            <!-- Rating -->
                            <div class="mb-3">
                                <label for="movieRating" class="form-label">RATING</label>
                                <select class="form-select" id="movieRating" name="movieRating" required>
                                    <option value="">Choose rating...</option>
                                    <option value="1" <?php echo ($movie['rating'] == 1) ? 'selected' : ''; ?>>★☆☆☆☆ 1 Star - Poor</option>
                                    <option value="2" <?php echo ($movie['rating'] == 2) ? 'selected' : ''; ?>>★★☆☆☆ 2 Stars - Below Average</option>
                                    <option value="3" <?php echo ($movie['rating'] == 3) ? 'selected' : ''; ?>>★★★☆☆ 3 Stars - Average</option>
                                    <option value="4" <?php echo ($movie['rating'] == 4) ? 'selected' : ''; ?>>★★★★☆ 4 Stars - Good</option>
                                    <option value="5" <?php echo ($movie['rating'] == 5) ? 'selected' : ''; ?>>★★★★★ 5 Stars - Excellent</option>
                                </select>
                            </div>

                            <!-- Date Watched -->
                            <div class="mb-3">
                                <label for="dateWatched" class="form-label">DATE WATCHED</label>
                                <input type="date" class="form-control" id="dateWatched" name="dateWatched" 
                                       value="<?php echo htmlspecialchars($movie['date_watched']); ?>" required>
                            </div>

                            <!-- Review (Textarea) -->
                            <div class="mb-3">
                                <label for="movieReview" class="form-label">YOUR REVIEW</label>
                                <textarea class="form-control" id="movieReview" name="movieReview" rows="4" required><?php echo htmlspecialchars($movie['review']); ?></textarea>
                                <div class="form-text">Share what you loved, what surprised you, or why you'd recommend it.</div>
                            </div>

                            <!-- Poster URL (Optional) -->
                            <div class="mb-4">
                                <label for="posterURL" class="form-label">POSTER IMAGE URL (Optional)</label>
                                <input type="url" class="form-control" id="posterURL" name="posterURL" 
                                       value="<?php echo htmlspecialchars($movie['poster_url']); ?>" 
                                       placeholder="https://example.com/poster.jpg">
                                <div class="form-text">
                                    <strong>How to get a poster URL:</strong><br>
                                    1. Google: "[Movie name] poster"<br>
                                    2. Right-click on image → "Copy image address"<br>
                                    3. Paste it here
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-save"></i> UPDATE MOVIE
                                </button>
                                <a href="index.php" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle"></i> CANCEL
                                </a>
                                <button type="button" class="btn btn-outline-danger" onclick="confirmDelete(<?php echo $movieId; ?>)">
                                    <i class="bi bi-trash"></i> DELETE MOVIE
                                </button>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="text-white text-center py-4 mt-5">
        <p class="mb-0" style="color: #808080;">© 2025 Movie Journal • Created for Activity 4</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function confirmDelete(movieId) {
            if (confirm('Are you sure you want to delete this movie? This action cannot be undone.')) {
                window.location.href = 'delete-movie.php?id=' + movieId;
            }
        }
    </script>
</body>
</html>
<?php
$conn->close();
?>