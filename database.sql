-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: movie_journal
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `directors`
--

DROP TABLE IF EXISTS `directors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `directors` (
  `director_id` int(11) NOT NULL AUTO_INCREMENT,
  `director_name` varchar(100) NOT NULL,
  `birth_year` int(11) DEFAULT NULL,
  `nationality` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`director_id`),
  KEY `idx_director_name` (`director_name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `directors`
--

LOCK TABLES `directors` WRITE;
/*!40000 ALTER TABLE `directors` DISABLE KEYS */;
INSERT INTO `directors` VALUES (1,'Christopher Nolan',1970,'British-American'),(2,'Frank Darabont',1959,'American'),(3,'Quentin Tarantino',1963,'American'),(4,'Robert Zemeckis',1952,'American'),(5,'Lana Wachowski',1965,'American'),(6,'Lilly Wachowski',1967,'American'),(7,'Joss Whedon',1964,'American'),(8,'James Ward Byrkit',1974,'American');
/*!40000 ALTER TABLE `directors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `genres`
--

DROP TABLE IF EXISTS `genres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `genres` (
  `genre_id` int(11) NOT NULL AUTO_INCREMENT,
  `genre_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`genre_id`),
  UNIQUE KEY `genre_name` (`genre_name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `genres`
--

LOCK TABLES `genres` WRITE;
/*!40000 ALTER TABLE `genres` DISABLE KEYS */;
INSERT INTO `genres` VALUES (1,'Action','High-energy films with physical stunts and chases'),(2,'Comedy','Films designed to elicit laughter'),(3,'Drama','Serious, plot-driven presentations'),(4,'Horror','Films intended to frighten and invoke fear'),(5,'Sci-Fi','Science fiction and futuristic themes'),(6,'Romance','Focus on romantic love stories'),(7,'Thriller','Suspenseful and exciting narratives'),(8,'Animation','Animated films using various techniques'),(9,'Documentary','Non-fictional motion pictures'),(10,'Crime','Films focusing on criminal activities');
/*!40000 ALTER TABLE `genres` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `movies`
--

DROP TABLE IF EXISTS `movies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `movies` (
  `movie_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `genre_id` int(11) NOT NULL,
  `director_id` int(11) DEFAULT NULL,
  `release_year` int(11) DEFAULT NULL,
  `poster_url` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`movie_id`),
  KEY `idx_title` (`title`),
  KEY `idx_genre` (`genre_id`),
  KEY `idx_director` (`director_id`),
  CONSTRAINT `movies_ibfk_1` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`genre_id`),
  CONSTRAINT `movies_ibfk_2` FOREIGN KEY (`director_id`) REFERENCES `directors` (`director_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movies`
--

LOCK TABLES `movies` WRITE;
/*!40000 ALTER TABLE `movies` DISABLE KEYS */;
INSERT INTO `movies` VALUES (1,'The Shawshank Redemption',3,2,1994,'https://m.media-amazon.com/images/M/MV5BMDAyY2FhYjctNDc5OS00MDNlLThiMGUtY2UxYWVkNGY2ZjljXkEyXkFqcGc@._V1_FMjpg_UX1000_.jpg','2026-01-13 05:49:53'),(2,'Inception',5,1,2010,'https://m.media-amazon.com/images/M/MV5BMjAxMzY3NjcxNF5BMl5BanBnXkFtZTcwNTI5OTM0Mw@@._V1_FMjpg_UX1000_.jpg','2026-01-13 05:49:53'),(3,'The Dark Knight',1,1,2008,'https://m.media-amazon.com/images/M/MV5BMTMxNTMwODM0NF5BMl5BanBnXkFtZTcwODAyMTk2Mw@@._V1_FMjpg_UX1000_.jpg','2026-01-13 05:49:53'),(4,'Pulp Fiction',10,3,1994,'https://m.media-amazon.com/images/M/MV5BYTViYTE3ZGQtNDBlMC00ZTAyLTkyODMtZGRiZDg0MjA2YThkXkEyXkFqcGc@._V1_QL75_UY281_CR2,0,190,281_.jpg','2026-01-13 05:49:53'),(5,'Forrest Gump',3,4,1994,'https://m.media-amazon.com/images/M/MV5BNDYwNzVjMTItZmU5YS00YjQ5LTljYjgtMjY2NDVmYWMyNWFmXkEyXkFqcGc@._V1_FMjpg_UX1000_.jpg','2026-01-13 05:49:53'),(6,'The Matrix',5,5,1999,'https://m.media-amazon.com/images/M/MV5BN2NmN2VhMTQtMDNiOS00NDlhLTliMjgtODE2ZTY0ODQyNDRhXkEyXkFqcGc@._V1_.jpg','2026-01-13 05:49:53'),(7,'Avengers',1,7,2012,'https://m.media-amazon.com/images/M/MV5BNGE0YTVjNzUtNzJjOS00NGNlLTgxMzctZTY4YTE1Y2Y1ZTU4XkEyXkFqcGc@._V1_.jpg','2026-01-13 05:49:53'),(8,'Coherence',4,8,2013,'https://m.media-amazon.com/images/M/MV5BNzQ3ODUzNDY2M15BMl5BanBnXkFtZTgwNzg0ODY2MTE@._V1_FMjpg_UX1000_.jpg','2026-01-13 05:49:53');
/*!40000 ALTER TABLE `movies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_reviews`
--

DROP TABLE IF EXISTS `user_reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_reviews` (
  `review_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `review_text` text NOT NULL,
  `date_watched` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`review_id`),
  UNIQUE KEY `unique_user_movie` (`user_id`,`movie_id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_movie` (`movie_id`),
  KEY `idx_rating` (`rating`),
  CONSTRAINT `user_reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `user_reviews_ibfk_2` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_reviews`
--

LOCK TABLES `user_reviews` WRITE;
/*!40000 ALTER TABLE `user_reviews` DISABLE KEYS */;
INSERT INTO `user_reviews` VALUES (1,1,1,5,'An inspiring story of hope and friendship in the darkest of places.','2026-01-03','2026-01-13 05:49:53','2026-01-13 05:49:53'),(2,1,2,5,'A mind-bending thriller about dreams within dreams. Absolutely brilliant!','2025-12-28','2026-01-13 05:49:53','2026-01-13 05:49:53'),(3,1,3,5,'Heath Ledger goated','2025-12-20','2026-01-13 05:49:53','2026-01-13 05:49:53'),(4,1,4,4,'Quentin Tarantino\'s masterpiece with incredible dialogue and storytelling.','2025-12-15','2026-01-13 05:49:53','2026-01-13 05:49:53'),(5,1,5,4,'Life is like a box of chocolates. A heartwarming and emotional journey.','2025-12-05','2026-01-13 05:49:53','2026-01-13 05:49:53'),(6,1,6,3,'Revolutionary visual effects and a thought-provoking storyline.','2025-11-28','2026-01-13 05:49:53','2026-01-13 05:49:53'),(7,1,7,3,'angas','2026-01-07','2026-01-13 05:49:53','2026-01-13 05:49:53'),(8,1,8,5,'Mind-boggling, unique horror movie','2025-02-21','2026-01-13 05:49:53','2026-01-13 05:49:53');
/*!40000 ALTER TABLE `user_reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_username` (`username`),
  KEY `idx_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'demo_user','demo@example.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','2026-01-13 05:49:53'),(2,'hello','hello@gmail.com','$2y$10$F1LH7YV7L2TB0pGFsUqrWexgSZlHsNhut5.fvhHib80a8MA0qbp5.','2026-01-13 05:53:22');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `vw_genre_stats`
--

DROP TABLE IF EXISTS `vw_genre_stats`;
/*!50001 DROP VIEW IF EXISTS `vw_genre_stats`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_genre_stats` AS SELECT 
 1 AS `genre_id`,
 1 AS `genre_name`,
 1 AS `total_movies`,
 1 AS `total_reviews`,
 1 AS `avg_rating`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_top_rated_movies`
--

DROP TABLE IF EXISTS `vw_top_rated_movies`;
/*!50001 DROP VIEW IF EXISTS `vw_top_rated_movies`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_top_rated_movies` AS SELECT 
 1 AS `user_id`,
 1 AS `username`,
 1 AS `title`,
 1 AS `genre_name`,
 1 AS `director_name`,
 1 AS `rating`,
 1 AS `date_watched`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_user_movie_collection`
--

DROP TABLE IF EXISTS `vw_user_movie_collection`;
/*!50001 DROP VIEW IF EXISTS `vw_user_movie_collection`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_user_movie_collection` AS SELECT 
 1 AS `user_id`,
 1 AS `username`,
 1 AS `movie_id`,
 1 AS `movie_title`,
 1 AS `genre_name`,
 1 AS `director_name`,
 1 AS `release_year`,
 1 AS `rating`,
 1 AS `review_text`,
 1 AS `date_watched`,
 1 AS `review_id`,
 1 AS `poster_url`*/;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `vw_genre_stats`
--

/*!50001 DROP VIEW IF EXISTS `vw_genre_stats`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_genre_stats` AS select `g`.`genre_id` AS `genre_id`,`g`.`genre_name` AS `genre_name`,count(distinct `m`.`movie_id`) AS `total_movies`,count(distinct `ur`.`review_id`) AS `total_reviews`,avg(`ur`.`rating`) AS `avg_rating` from ((`genres` `g` left join `movies` `m` on(`g`.`genre_id` = `m`.`genre_id`)) left join `user_reviews` `ur` on(`m`.`movie_id` = `ur`.`movie_id`)) group by `g`.`genre_id`,`g`.`genre_name` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_top_rated_movies`
--

/*!50001 DROP VIEW IF EXISTS `vw_top_rated_movies`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_top_rated_movies` AS select `u`.`user_id` AS `user_id`,`u`.`username` AS `username`,`m`.`title` AS `title`,`g`.`genre_name` AS `genre_name`,`d`.`director_name` AS `director_name`,`ur`.`rating` AS `rating`,`ur`.`date_watched` AS `date_watched` from ((((`user_reviews` `ur` join `users` `u` on(`ur`.`user_id` = `u`.`user_id`)) join `movies` `m` on(`ur`.`movie_id` = `m`.`movie_id`)) join `genres` `g` on(`m`.`genre_id` = `g`.`genre_id`)) left join `directors` `d` on(`m`.`director_id` = `d`.`director_id`)) where `ur`.`rating` >= 4 order by `ur`.`rating` desc,`ur`.`date_watched` desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_user_movie_collection`
--

/*!50001 DROP VIEW IF EXISTS `vw_user_movie_collection`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_user_movie_collection` AS select `u`.`user_id` AS `user_id`,`u`.`username` AS `username`,`m`.`movie_id` AS `movie_id`,`m`.`title` AS `movie_title`,`g`.`genre_name` AS `genre_name`,`d`.`director_name` AS `director_name`,`m`.`release_year` AS `release_year`,`ur`.`rating` AS `rating`,`ur`.`review_text` AS `review_text`,`ur`.`date_watched` AS `date_watched`,`ur`.`review_id` AS `review_id`,`m`.`poster_url` AS `poster_url` from ((((`user_reviews` `ur` join `users` `u` on(`ur`.`user_id` = `u`.`user_id`)) join `movies` `m` on(`ur`.`movie_id` = `m`.`movie_id`)) join `genres` `g` on(`m`.`genre_id` = `g`.`genre_id`)) left join `directors` `d` on(`m`.`director_id` = `d`.`director_id`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-13 14:20:32
