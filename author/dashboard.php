<?php

session_start();

require_once "../includes/db.php";

// Login check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Only author can access
if ($_SESSION['user_role'] !== 'author') {
    header("Location: ../admin/dashboard.php");
    exit;
}

$authorId = (int) $_SESSION['user_id'];
$authorName = $_SESSION['user_name'] ?? 'Author';

// Author ke posts count
$totalPosts = 0;
$publishedPosts = 0;
$draftPosts = 0;

$countQuery = "
    SELECT
        COUNT(*) AS total_posts,
        SUM(CASE WHEN status = 'published' THEN 1 ELSE 0 END) AS published_posts,
        SUM(CASE WHEN status != 'published' THEN 1 ELSE 0 END) AS draft_posts
    FROM posts
    WHERE author_id = $authorId
";

$countResult = mysqli_query($conn, $countQuery);

if ($countResult) {
    $countData = mysqli_fetch_assoc($countResult);

    $totalPosts = (int) ($countData['total_posts'] ?? 0);
    $publishedPosts = (int) ($countData['published_posts'] ?? 0);
    $draftPosts = (int) ($countData['draft_posts'] ?? 0);
}

// Recent posts
$recentPosts = [];

$postsQuery = "
    SELECT id, title, status, created_at
    FROM posts
    WHERE author_id = $authorId
    ORDER BY created_at DESC
    LIMIT 5
";

$postsResult = mysqli_query($conn, $postsQuery);

if ($postsResult) {
    while ($row = mysqli_fetch_assoc($postsResult)) {
        $recentPosts[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Author Dashboard | Mora Blog</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f6fa;
            color: #333;
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 240px;
            height: 100vh;
            background: #111827;
            color: white;
            padding: 25px 15px;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            color: #ddd;
            text-decoration: none;
            padding: 13px 15px;
            margin-bottom: 8px;
            border-radius: 6px;
        }

        .sidebar a:hover {
            background: #374151;
            color: white;
        }

        .logout {
            margin-top: 30px;
            background: #dc2626;
        }

        .main {
            margin-left: 240px;
            padding: 30px;
        }

        .topbar {
            background: white;
            padding: 20px 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar h1 {
            font-size: 25px;
        }

        .welcome {
            color: #666;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .card h3 {
            color: #666;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .card .number {
            font-size: 32px;
            font-weight: bold;
        }

        .content-box {
            background: white;
            padding: 25px;
            border-radius: 10px;
        }

        .content-box h2 {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 13px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        th {
            background: #f8f9fa;
        }

        .status {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
        }

        .published {
            background: #dcfce7;
            color: #166534;
        }

        .draft {
            background: #fef3c7;
            color: #92400e;
        }

        @media (max-width: 768px) {

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main {
                margin-left: 0;
            }

            .cards {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>

<body>

<div class="sidebar">

    <h2>Mora Blog</h2>

    <a href="dashboard.php">Dashboard</a>

    <a href="posts/">My Posts</a>

    <a href="../">View Website</a>

    <a href="logout.php" class="logout">Logout</a>

</div>

<div class="main">

    <div class="topbar">

        <div>
            <h1>Author Dashboard</h1>
            <p class="welcome">
                Welcome, <?php echo htmlspecialchars($authorName); ?>
            </p>
        </div>

        <div>
            <strong>Author</strong>
        </div>

    </div>

    <div class="cards">

        <div class="card">

            <h3>Total Posts</h3>

            <div class="number">
                <?php echo $totalPosts; ?>
            </div>

        </div>

        <div class="card">

            <h3>Published Posts</h3>

            <div class="number">
                <?php echo $publishedPosts; ?>
            </div>

        </div>

        <div class="card">

            <h3>Draft Posts</h3>

            <div class="number">
                <?php echo $draftPosts; ?>
            </div>

        </div>

    </div>

    <div class="content-box">

        <h2>Recent Posts</h2>

        <?php if (count($recentPosts) > 0): ?>

            <table>

                <thead>

                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Created</th>
                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($recentPosts as $post): ?>

                        <tr>

                            <td>
                                <?php echo htmlspecialchars($post['title']); ?>
                            </td>

                            <td>

                                <?php if ($post['status'] === 'published'): ?>

                                    <span class="status published">
                                        Published
                                    </span>

                                <?php else: ?>

                                    <span class="status draft">
                                        <?php echo htmlspecialchars($post['status']); ?>
                                    </span>

                                <?php endif; ?>

                            </td>

                            <td>
                                <?php echo htmlspecialchars($post['created_at']); ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        <?php else: ?>

            <p>No posts found for this author.</p>

        <?php endif; ?>

    </div>

</div>

</body>
</html>