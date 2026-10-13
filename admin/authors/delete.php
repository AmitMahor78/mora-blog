<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

include "../../includes/db.php";

$authorId = isset($_GET['id'])
    ? intval($_GET['id'])
    : 0;

if ($authorId <= 0) {
    header("Location: index.php");
    exit;
}


/*
    Prevent admin from deleting himself
*/
if ($authorId == $_SESSION['user_id']) {
    header("Location: index.php?error=delete_failed");
    exit;
}


/*
    Check whether author exists
*/
$authorQuery = "
    SELECT id
    FROM users
    WHERE id = $authorId
    AND role = 'author'
    LIMIT 1
";

$authorResult = mysqli_query($conn, $authorQuery);

if (!$authorResult || mysqli_num_rows($authorResult) === 0) {
    header("Location: index.php");
    exit;
}


/*
    Check author's posts
*/
$postQuery = "
    SELECT COUNT(*) AS total_posts
    FROM posts
    WHERE author_id = $authorId
";

$postResult = mysqli_query($conn, $postQuery);

if (!$postResult) {
    header("Location: index.php?error=delete_failed");
    exit;
}

$postData = mysqli_fetch_assoc($postResult);

$totalPosts = intval($postData['total_posts']);


/*
    Do not delete author if posts exist
*/
if ($totalPosts > 0) {

    header("Location: index.php?error=has_posts");
    exit;
}


/*
    Delete author
*/
$deleteQuery = "
    DELETE FROM users
    WHERE id = $authorId
    AND role = 'author'
";

if (mysqli_query($conn, $deleteQuery)) {

    header("Location: index.php?success=deleted");
    exit;
}


header("Location: index.php?error=delete_failed");
exit;
?>