<?php
require_once 'config.php';
require_once 'auth.php';

requireLogin();

$userId = getCurrentUserId();

$sql = "SELECT * FROM vw_user_movie_collection WHERE user_id = ? ORDER BY date_watched DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>4th Wall Movie Journal - My Library</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            background-attachment: fixed;
            color: #e0e0e0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: rgba(0, 0, 0, 0.9) !important;
            backdrop-filter: blur(10px);
            border-bottom: 2px solid #d4af37;
            box-shadow: 0 4px 30px rgba(212, 175, 55, 0.3);
        }

        .navbar-brand {
            color: #d4af37 !important;
            font-weight: bold;
            font-size: 1.5rem;
            text-shadow: 0 0 10px rgba(212, 175, 55, 0.5);
        }

        .nav-link {
            color: #c0c0c0 !important;
            transition: all 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: #d4af37 !important;
            text-shadow: 0 0 8px rgba(212, 175, 55, 0.6);
        }

        .cinema-hero {
            position: relative;
            height: 500px;
            background: linear-gradient(to bottom, rgba(0,0,0,0.7), rgba(15,12,41,0.95)), 
                        url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 800"><rect fill="%23000" width="1200" height="800"/></svg>');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 4rem;
            overflow: hidden;
            border-bottom: 3px solid #d4af37;
        }

        .cinema-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at center, transparent 0%, rgba(0,0,0,0.8) 100%);
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 2rem;
        }

        .cinema-meme {
            max-width: 600px;
            width: 100%;
            height: auto;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(212, 175, 55, 0.3);
            margin-bottom: 2rem;
            border: 3px solid rgba(212, 175, 55, 0.4);
            transition: all 0.4s ease;
        }

        .cinema-meme:hover {
            transform: scale(1.02);
            box-shadow: 0 25px 80px rgba(212, 175, 55, 0.5);
            border-color: #d4af37;
        }

        h1 {
            color: #d4af37;
            text-shadow: 0 0 20px rgba(212, 175, 55, 0.4);
            font-weight: bold;
        }
        
        .movie-card {
            background: rgba(20, 20, 30, 0.8);
            border: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: 10px;
            transition: all 0.3s ease;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
        }

        .movie-card:hover {
            transform: translateY(-10px);
            border-color: #d4af37;
            box-shadow: 0 12px 40px rgba(212, 175, 55, 0.4);
        }

        .movie-card img {
            border-bottom: 2px solid rgba(212, 175, 55, 0.3);
            transition: all 0.3s ease;
            width: 100%;
            height: 450px;
            object-fit: cover;
        }

        .movie-card:hover img {
            filter: brightness(1.1);
        }

        .card-body {
            background: rgba(15, 15, 25, 0.9);
        }

        .card-title {
            color: #d4af37;
            font-weight: bold;
        }

        .card-text {
            color: #b0b0b0;
        }
        
        .badge {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            box-shadow: 0 0 10px rgba(212, 175, 55, 0.3);
        }

        .badge.bg-warning {
            background: linear-gradient(135deg, #d4af37, #f4e6a7) !important;
            color: #1a1a2e !important;
        }

        .badge.bg-success {
            background: linear-gradient(135deg, #4a90e2, #67b8ff) !important;
        }

        .badge.bg-info {
            background: linear-gradient(135deg, #667eea, #764ba2) !important;
        }

        .btn-outline-warning {
            border-color: rgba(212, 175, 55, 0.6);
            color: #d4af37;
        }

        .btn-outline-warning:hover {
            background: rgba(212, 175, 55, 0.2);
            border-color: #d4af37;
            color: #d4af37;
        }

        footer {
            background: rgba(0, 0, 0, 0.9) !important;
            border-top: 2px solid #d4af37;
            box-shadow: 0 -4px 30px rgba(212, 175, 55, 0.2);
        }

        .container::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 10px;
            background: repeating-linear-gradient(
                90deg,
                #d4af37 0px,
                #d4af37 20px,
                transparent 20px,
                transparent 40px
            );
            z-index: 1000;
            opacity: 0.3;
        }
        
        .section-title {
            position: relative;
            padding-bottom: 1rem;
            margin-bottom: 3rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, transparent, #d4af37, transparent);
        }

        .alert {
            background: rgba(76, 175, 80, 0.2);
            border: 1px solid #4caf50;
            color: #a5d6a7;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid #dc3545;
            color: #f8d7da;
        }
        
        .user-badge {
            background: rgba(212, 175, 55, 0.2);
            border: 1px solid rgba(212, 175, 55, 0.4);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            color: #d4af37;
        }

        .movie-meta {
            color: #999;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php"><i class="bi bi-film"></i> 4TH WALL MOVIE JOURNAL</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">HOME</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="add-movie.php">ADD MOVIE</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="top-rated.php">TOP RATED</a>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link user-badge">
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

    <!-- ABSOLTUE CINEMA -->
    <div class="cinema-hero">
        <div class="hero-content">
            <img src="images/cinema.webp" alt="Absolute Cinema" class="cinema-meme">
        </div>
    </div>

    <!-- MAIN CONTENT AREA -->
    <div class="container">
        <h1 class="text-center mb-2 section-title">MY CINEMA COLLECTION</h1>
        <p class="text-center mb-5" style="color: #808080; font-style: italic;">A collection of <?php echo $result->num_rows; ?> cinematic experiences</p>

        <!-- DELETE SUCCESS/ERROR MESSAGES -->
        <?php if (isset($_GET['deleted'])): ?>
            <?php if ($_GET['deleted'] == 'success'): ?>
            <div class="alert alert-dismissible fade show" role="alert">
                <strong><i class="bi bi-check-circle"></i> Success!</strong> Movie deleted successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php elseif ($_GET['deleted'] == 'error'): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="bi bi-exclamation-triangle"></i> Error!</strong> Failed to delete movie.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- GRID OF MOVIE CARDS -->
        <div class="row">
            
            <?php
            if ($result->num_rows > 0) {
                while($movie = $result->fetch_assoc()) {
                    $badgeClass = 'bg-warning';
                    if ($movie['rating'] == 5) {
                        $badgeClass = 'bg-warning';
                    } elseif ($movie['rating'] == 4) {
                        $badgeClass = 'bg-success';
                    } else {
                        $badgeClass = 'bg-info';
                    }
                    
                    $stars = str_repeat('★', $movie['rating']) . str_repeat('☆', 5 - $movie['rating']);
                    
                    if (!empty($movie['poster_url'])) {
                        $posterUrl = $movie['poster_url'];
                    } else {
                        $posterUrl = 'https://dummyimage.com/300x450/1a1a2e/d4af37.png&text=' . urlencode($movie['movie_title']);
                    }
            ?>
            
            <!-- Movie Card -->
            <div class="col-md-4 mb-4">
                <div class="card h-100 movie-card">
                    <img src="<?php echo htmlspecialchars($posterUrl); ?>" 
                         class="card-img-top" 
                         alt="<?php echo htmlspecialchars($movie['movie_title']); ?>"
                         onerror="this.onerror=null; this.src='https://dummyimage.com/300x450/1a1a2e/d4af37.png&text=Movie+Poster';">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($movie['movie_title']); ?></h5>
                        <div class="movie-meta">
                            <?php if (!empty($movie['director_name'])): ?>
                                <i class="bi bi-person-fill"></i> <?php echo htmlspecialchars($movie['director_name']); ?><br>
                            <?php endif; ?>
                            <?php if (!empty($movie['release_year'])): ?>
                                <i class="bi bi-calendar"></i> <?php echo $movie['release_year']; ?>
                            <?php endif; ?>
                        </div>
                        <p class="card-text"><strong style="color: #d4af37;">Genre:</strong> <?php echo htmlspecialchars($movie['genre_name']); ?></p>
                        <p class="card-text"><?php echo htmlspecialchars($movie['review_text']); ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge <?php echo $badgeClass; ?>"><?php echo $stars; ?> <?php echo $movie['rating']; ?>/5</span>
                            <a href="edit-movie.php?id=<?php echo $movie['review_id']; ?>" class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php
                }
            } else {
                echo '<div class="col-12 text-center">';
                echo '<p style="color: #808080; font-size: 1.2rem;">No movies in your collection yet. <a href="add-movie.php" style="color: #d4af37;">Add your first movie!</a></p>';
                echo '</div>';
            }
            
            $stmt->close();
            $conn->close();
            ?>

        </div>
    </div>

    <!-- FOOTER -->
    <footer class="text-white text-center py-4 mt-5">
        <p class="mb-0" style="color: #808080;">© 2026 4th Wall • Group 10 - Final Project</p>
    </footer>

    <!-- Bootstrap 5 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>