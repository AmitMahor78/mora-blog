-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: mora_blog
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `mora_blog`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `mora_blog` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `mora_blog`;

--
-- Table structure for table `advertisements`
--

DROP TABLE IF EXISTS `advertisements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `advertisements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `link` varchar(500) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `advertisements`
--

LOCK TABLES `advertisements` WRITE;
/*!40000 ALTER TABLE `advertisements` DISABLE KEYS */;
INSERT INTO `advertisements` VALUES (5,'Shoes','uploads/advertisements/db36f7cec488ad1cd40cf0f507182eab.jpg','https://echosabove.com/products/away-day-forest-green?gad_source=1&gad_campaignid=24133560388&gbraid=0AAAABC-hx-SMtFzXDB6a_nIAibRgsIXCl&gclid=CjwKCAjwoaLWBhAWEiwAnyitu9FFLBVhvYp4uOUJjkATsZ3gaQUWSR1wxPW0_FzXiOenwmjLYmyFDhoCXKwQAvD_BwE','active','2026-10-09 15:12:01'),(6,'Book','uploads/advertisements/85245ee1e9f7ad7d1c4b47645c4814cd.jpeg','https://www.amazon.in/Secret-Joyful-Relationships-Acharya-Prashant/dp/9395257652','active','2026-10-09 15:13:59');
/*!40000 ALTER TABLE `advertisements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `authors`
--

DROP TABLE IF EXISTS `authors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `authors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authors`
--

LOCK TABLES `authors` WRITE;
/*!40000 ALTER TABLE `authors` DISABLE KEYS */;
INSERT INTO `authors` VALUES (1,'Amit Mahor','images/post/author-1.jpg','Mora Blog administrator and content contributor.','2026-10-05 13:59:32');
/*!40000 ALTER TABLE `authors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Business','business'),(2,'Travel','travel'),(3,'Fashion','fashion'),(4,'Technology','technology'),(5,'Gaming','gaming'),(6,'Watch It','watch-it');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (1,1,'Amit','amit@example.com','This is a very useful and informative post.','2026-10-05 14:04:29'),(2,1,'Rahul','rahul@example.com','Great article. I really enjoyed reading it.','2026-10-05 14:04:29'),(3,1,'Priya','priya@example.com','Very interesting information. Thanks for sharing.','2026-10-05 14:04:29'),(4,1,'Ankit','ankitmahor@gmail.com','Good News','2026-10-05 14:15:42'),(5,5,'Arun Pratap','Arunpratap@gmail.com','Badiya News','2026-10-05 14:16:27'),(6,5,'Anup Mahor','anup@gmail.com','Good News','2026-10-05 14:23:38'),(7,1,'Amit Mahor','amitmahor613@gmail.com','Nice Video','2026-10-06 17:36:59'),(8,1,'Amit Mahor','anamikamahor5@gmail.com','Nice','2026-10-07 18:16:29'),(9,5,'Amit Mahor','anamikamahor5@gmail.com','Nice','2026-10-08 14:21:42');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `company` varchar(150) DEFAULT NULL,
  `request_type` varchar(100) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('new','read','replied') DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
INSERT INTO `contact_messages` VALUES (1,'Amit Mahor','anamikamahor5@gmail.com','Xd Code Club','Travel Post','Hii, I want to Travel Post','read','2026-10-06 14:42:27'),(2,'Amit Mahor','amitmahor613@gmail.com','Xd Code Club','Tech Post','I want show my technology post','read','2026-10-09 19:07:32');
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `author_id` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,'Welcome to Mora','welcome-to-mora-blog','This is our first dynamic blog post.','This is the complete content of our first dynamic blog post. Later this content will be managed from the admin panel.','images/sample.jpg',1,1,'published','2026-10-02 14:26:14','2026-10-02 16:01:48'),(2,'Beautiful Places to Visit','beautiful-places-to-visit','Explore some beautiful travel destinations.','This is a travel post about beautiful places to visit.','images/post/travel-post-1.jpg',2,1,'published','2026-10-02 16:41:00','2026-10-02 16:41:00'),(3,'Watch It Demo Post','watch-it-demo-post','This is a demo Watch It post.','This is the complete content of the Watch It demo post.','images/post/wathc-1.jpg',6,1,'published','2026-10-02 17:55:23','2026-10-02 17:55:23'),(4,'Watch It Second Demo Post','watch-it-second-demo-post','This is a second demo Watch It post.','This is the complete content of the Watch It secind demo post.','images/post/wathc-1.jpg',6,1,'published','2026-10-02 17:57:23','2026-10-02 17:57:23'),(5,'Latest Fashion Trends','latest-fashion-trends','Discover the latest fashion trends and styles.','This is a sample fashion article for Mora Blog.','images/post/fashion-1.jpg',3,1,'published','2026-10-02 18:05:45','2026-10-02 18:05:45'),(7,'For Business','for-business','Exapmle for Business Post 2','Exapmle for Business Post 2','uploads/posts/post_6ac7bccdbe3eb0.66935502.jpg',1,3,'published','2026-10-08 15:54:53','2026-10-10 15:20:58');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Stockholm T-shirt',86.00,'images/shop/shop-1.jpg','T-shirt','Comfortable Stockholm T-shirt.','active','2026-10-08 14:28:29'),(2,'T-shirt Rest in Pizza',19.00,'images/shop/shop-2.jpg','T-shirt','Stylish Rest in Pizza T-shirt.','active','2026-10-08 14:28:29'),(3,'T-shirt Pocket Local',79.00,'images/shop/shop-3.jpg','T-shirt','Premium pocket local T-shirt.','active','2026-10-08 14:28:29'),(4,'T-shirt Rest in Pizza',9.00,'images/shop/shop-4.jpg','T-shirt','Affordable casual T-shirt.','active','2026-10-08 14:28:29'),(5,'Key to the City',39.00,'images/shop/shop-5.jpg','Concept','Key to the City product.','active','2026-10-08 14:28:29'),(6,'Clean White Shirt',36.00,'images/shop/shop-6.jpg','Cloth','Clean white shirt.','active','2026-10-08 14:28:29'),(7,'Blue T Shirt Woman',19.00,'uploads/products/f08383c6fc14ad20ed0a9dde2414f983.jpg','Cloth','Blue T-shirt for women.','active','2026-10-08 14:28:29'),(8,'T-shirt Double Daffy',90.00,'uploads/products/b7ee90a60eb84de9d135bbb5a59e745d.jpg','T-shirt','Double Daffy printed T-shirt.','active','2026-10-08 14:28:29'),(9,'T-shirt Vinyl Spin',78.00,'uploads/products/bb50253e934e7c78889562cd2f22776b.jpg','T-shirt','Vinyl Spin T-shirt.','active','2026-10-08 14:28:29'),(10,'T-Shirt 16',100.00,'uploads/products/b3476391d0bce1b4d6d8b722e92a686c.jpg','Fashion','Buy One Get Only One','active','2026-10-09 16:13:02');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registered_users`
--

DROP TABLE IF EXISTS `registered_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `registered_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registered_users`
--

LOCK TABLES `registered_users` WRITE;
/*!40000 ALTER TABLE `registered_users` DISABLE KEYS */;
INSERT INTO `registered_users` VALUES (1,'Amit Mahor','amitmahor613@sololearn.temp','$2y$10$2ZOd8QXFDdRQTXgl7zumpO8SmqHf4e.k/PUOYQFazdsOczAw39oJK','active','2026-10-06 15:03:06');
/*!40000 ALTER TABLE `registered_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_settings`
--

DROP TABLE IF EXISTS `site_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_name` varchar(100) NOT NULL,
  `site_description` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `working_days` varchar(100) DEFAULT NULL,
  `closing_day` varchar(100) DEFAULT NULL,
  `copyright_year` varchar(10) DEFAULT NULL,
  `developer_name` varchar(100) DEFAULT NULL,
  `developer_url` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_settings`
--

LOCK TABLES `site_settings` WRITE;
/*!40000 ALTER TABLE `site_settings` DISABLE KEYS */;
INSERT INTO `site_settings` VALUES (1,'Mora','Established in 2026 as the first cloud mining provider, Blog has become a multi-functional Blog Categories, trusted by over a million users.','Morena Madhya Pradesh','Monday-Saturday','Sunday','2026','Amit Mahor','https://amitmahor78.github.io/portfoliom/','active','2026-10-06 13:50:48','2026-10-09 17:08:27');
/*!40000 ALTER TABLE `site_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `social_links`
--

DROP TABLE IF EXISTS `social_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `social_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `platform` varchar(50) NOT NULL,
  `url` varchar(500) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `social_links`
--

LOCK TABLES `social_links` WRITE;
/*!40000 ALTER TABLE `social_links` DISABLE KEYS */;
INSERT INTO `social_links` VALUES (1,'facebook','https://facebook.com/','active','2026-10-05 17:09:50'),(2,'twitter','https://twitter.com/','active','2026-10-05 17:09:50'),(3,'pinterest','https://pinterest.com/','active','2026-10-05 17:09:50'),(4,'linkedin','https://linkedin.com/','active','2026-10-05 17:09:50'),(5,'google','https://google.com/','active','2026-10-05 17:09:50'),(6,'instagram','https://instagram.com/amit_mahor_16','active','2026-10-05 17:09:50');
/*!40000 ALTER TABLE `social_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscribers`
--

DROP TABLE IF EXISTS `subscribers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscribers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `subscribed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscribers`
--

LOCK TABLES `subscribers` WRITE;
/*!40000 ALTER TABLE `subscribers` DISABLE KEYS */;
INSERT INTO `subscribers` VALUES (1,'anamikamahor5@gmail.com','active','2026-10-06 14:03:08'),(2,'amitmahor613@gmail.com','active','2026-10-06 14:05:57'),(3,'mahorsahab244@gmail.com','active','2026-10-06 17:40:09');
/*!40000 ALTER TABLE `subscribers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT 'author',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Amit','admin@mora-blog.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC7F8Z8xZ8xZ8xZ8xZ8u','admin','2026-10-02 14:25:49'),(2,'Administrator','admin@mora.com','$2y$10$OQSKFVhbNSMap.x.hwRu1ORt25bEhzn/40iLH9f2slGxjpXFiic.G','admin','2026-10-08 15:07:48'),(3,'Demo Author','author@mora.com','$2y$10$WuPxn3R9gSSUJ1qQn/31JO71apXkw9BzSQWJyLr6kBgxnVXC1hUxi','author','2026-10-08 15:22:35');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `videos`
--

DROP TABLE IF EXISTS `videos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `video_url` varchar(500) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `videos`
--

LOCK TABLES `videos` WRITE;
/*!40000 ALTER TABLE `videos` DISABLE KEYS */;
/*!40000 ALTER TABLE `videos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'mora_blog'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-10 20:51:49
