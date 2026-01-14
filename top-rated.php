<?php
require_once 'config.php';
require_once 'auth.php';

requireLogin();

$userId = getCurrentUserId();
$moviesPerPage = 10;
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($currentPage - 1) * $moviesPerPage;

$totalResult = $conn->query("SELECT COUNT(*) as total FROM user_reviews WHERE user_id = $userId");
$totalMovies = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalMovies / $moviesPerPage);

$sql = "SELECT * FROM vw_user_movie_collection 
        WHERE user_id = ? 
        ORDER BY rating DESC, date_watched DESC 
        LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $userId, $moviesPerPage, $offset);
$stmt->execute();
$result = $stmt->get_result();

$statsStmt = $conn->prepare("CALL sp_get_user_stats(?)");
$statsStmt->bind_param("i", $userId);
$statsStmt->execute();
$statsResult = $statsStmt->get_result();
$stats = $statsResult->fetch_assoc();
$statsStmt->close();

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$avgRating = $totalMovies > 0 ? number_format($stats['avg_rating'] ?? 0, 1) : '0.0';
$favoriteGenre = $stats['favorite_genre'] ?? 'N/A';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Rated Movies - Movie Journal</title>
    
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
                        <a class="nav-link active" href="top-rated.php">TOP RATED</a>
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
        <h1 class="text-center mb-2"><i class="bi bi-trophy"></i> MY CRITICS' CHOICE</h1>
        <p class="text-center mb-5" style="color: #808080; font-style: italic;">My highest-rated cinematic masterpieces</p>

        <!-- TABLE WRAPPER -->
        <div class="card shadow-lg mb-5">
            <div class="card-body p-0">
                
                <!-- RESPONSIVE TABLE -->
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 60px;">#</th>
                                <th scope="col">Title</th>
                                <th scope="col">Director</th>
                                <th scope="col">Genre</th>
                                <th scope="col">Rating</th>
                                <th scope="col">Date Watched</th>
                                <th scope="col" style="width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            <?php
                            if ($result->num_rows > 0) {
                                $rowNumber = $offset + 1;
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
                                    
                                    $dateFormatted = !empty($movie['date_watched']) ? date('M d, Y', strtotime($movie['date_watched'])) : 'Not set';
                            ?>
                            
                            <tr>
                                <th scope="row"><?php echo $rowNumber; ?></th>
                                <td><?php echo htmlspecialchars($movie['movie_title']); ?></td>
                                <td><?php echo htmlspecialchars($movie['director_name'] ?? 'Unknown'); ?></td>
                                <td><?php echo htmlspecialchars($movie['genre_name']); ?></td>
                                <td><span class="badge <?php echo $badgeClass; ?>"><?php echo $stars; ?></span></td>
                                <td><?php echo !empty($movie['date_watched']) ? date('M d, Y', strtotime($movie['date_watched'])) : 'Not set'; ?></td>
                                <td>
                                    <a href="edit-movie.php?id=<?php echo $movie['review_id']; ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                </td>
                            </tr>
                            
                            <?php
                                    $rowNumber++;
                                }
                            } else {
                                echo '<tr><td colspan="7" class="text-center" style="color: #808080;">No movies found. <a href="add-movie.php" style="color: #d4af37;">Add your first movie!</a></td></tr>';
                            }
                            ?>

                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION -->
                <?php if ($totalPages > 1): ?>
                <div class="p-3 border-top" style="border-color: rgba(212, 175, 55, 0.2) !important;">
                    <nav aria-label="Movie list pagination">
                        <ul class="pagination justify-content-center mb-0">

                            <li class="page-item <?php echo ($currentPage <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>" tabindex="-1">Previous</a>
                            </li>
                            
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                            <?php endfor; ?>
                            
                            <li class="page-item <?php echo ($currentPage >= $totalPages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <?php endif; ?>

            </div>
        </div>

        <!-- STATISTICS CARDS -->
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card text-center stat-card">
                    <div class="card-body py-4">
                        <h5 class="card-title">Total Films</h5>
                        <h2 class="display-4 mb-0"><?php echo $totalMovies; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center stat-card">
                    <div class="card-body py-4">
                        <h5 class="card-title">Average Rating</h5>
                        <h2 class="display-4 mb-0"><?php echo $avgRating; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center stat-card">
                    <div class="card-body py-4">
                        <h5 class="card-title">Favorite Genre</h5>
                        <h2 class="display-4 mb-0" style="font-size: 2.5rem;"><?php echo htmlspecialchars($favoriteGenre); ?></h2>
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
$stmt->close();
$conn->close();
?>