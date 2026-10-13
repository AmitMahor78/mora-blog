<?php

session_start();

require_once "../../includes/db.php";

// Login check
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Only author can access
if ($_SESSION['user_role'] !== 'author') {
    header("Location: ../../admin/dashboard.php");
    exit;
}

$authorId = (int) $_SESSION['user_id'];
$authorName = $_SESSION['user_name'] ?? 'Author';

// Fetch author's posts
$query = "
    SELECT 
        p.id,
        p.title,
        p.status,
        p.created_at,
        c.name AS category_name
    FROM posts p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.author_id = $authorId
    ORDER BY p.created_at DESC
";

$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Posts | Mora Blog</title>

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

        .add-btn {
            background: #2563eb;
            color: white;
            text-decoration: none;
            padding: 11px 18px;
            border-radius: 6px;
        }

        .table-box {
            background: white;
            padding: 25px;
            border-radius: 10px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 14px;
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

        .edit-btn {
            background: #2563eb;
            color: white;
            text-decoration: none;
            padding: 6px 10px;
            border-radius: 4px;
            margin-right: 5px;
        }

        .delete-btn {
            background: #dc2626;
            color: white;
            text-decoration: none;
            padding: 6px 10px;
            border-radius: 4px;
        }

        .empty {
            text-align: center;
            padding: 30px;
            color: #777;
        }

    </style>

</head>

<body>

<div class="sidebar">

    <h2>Mora Blog</h2>

    <a href="../dashboard.php">Dashboard</a>

    <a href="index.php">My Posts</a>

    <a href="create.php">Create Post</a>

    <a href="../../index.php">View Website</a>

    <a href="../logout.php" class="logout">Logout</a>

</div>

<div class="main">

    <div class="topbar">

        <div>

            <h1>My Posts</h1>

            <p>
                Welcome, <?php echo htmlspecialchars($authorName); ?>
            </p>

        </div>

        <a href="create.php" class="add-btn">
            + Create New Post
        </a>

    </div>

    <div class="table-box">

        <?php if ($result && mysqli_num_rows($result) > 0): ?>

            <table>

                <thead>

                    <tr>

                        <th>ID</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                <?php while ($post = mysqli_fetch_assoc($result)): ?>

                    <tr>

                        <td>
                            <?php echo $post['id']; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($post['title']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($post['category_name'] ?? 'Uncategorized'); ?>
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

                        <td>

                            <a
                                href="edit.php?id=<?php echo $post['id']; ?>"
                                class="edit-btn"
                            >
                                Edit
                            </a>

                            <a
                                href="delete.php?id=<?php echo $post['id']; ?>"
                                class="delete-btn"
                                onclick="return confirm('Are you sure you want to delete this post?');"
                            >
                                Delete
                            </a>

                        </td>

                    </tr>

                <?php endwhile; ?>

                </tbody>

            </table>

        <?php else: ?>

            <div class="empty">
                No posts found.
            </div>

        <?php endif; ?>

    </div>

</div>

</body>

</html>