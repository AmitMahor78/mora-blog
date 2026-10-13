<?php

session_start();

require_once "../../includes/db.php";

// Login check
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Only author
if ($_SESSION['user_role'] !== 'author') {
    header("Location: ../../admin/dashboard.php");
    exit;
}

$authorId = (int) $_SESSION['user_id'];

$postId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($postId <= 0) {
    header("Location: index.php");
    exit;
}

// Delete only author's own post
$deleteQuery = "
    DELETE FROM posts
    WHERE id = $postId
    AND author_id = $authorId
";

if (mysqli_query($conn, $deleteQuery)) {

    header("Location: index.php?success=deleted");
    exit;

} else {

    die("Unable to delete post: " . mysqli_error($conn));

}

?>