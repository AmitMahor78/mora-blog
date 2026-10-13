<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

include "../../includes/db.php";

$query = "
    SELECT 
        p.id,
        p.title,
        p.status,
        p.created_at,
        c.name AS category_name,
        u.name AS author_name
    FROM posts p
    LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN users u ON p.author_id = u.id
    ORDER BY p.created_at DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Posts - Admin Panel</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f6f9;
            color: #333;
        }

        .topbar {
            background: #222;
            color: white;
            padding: 18px 30px;
            display: flex;
            margin-left: 260px;
            justify-content: space-between;
            align-items: center;
        }
        

        .topbar h2 {
            font-size: 22px;
        }

        .topbar a {
            color: white;
            text-decoration: none;
            background: #e74c3c;
            padding: 9px 15px;
            border-radius: 5px;
        }

        .container {
            margin-left: 260px;
            padding: 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .header h1 {
            font-size: 28px;
        }

        .btn {
            text-decoration: none;
            color: white;
            background: #007bff;
            padding: 10px 16px;
            border-radius: 5px;
            display: inline-block;
        }

        .btn:hover {
            background: #0056b3;
        }

        .table-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 13px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #f1f1f1;
        }

        .status {
            padding: 5px 9px;
            border-radius: 4px;
            font-size: 13px;
        }

        .published {
            background: #d4edda;
            color: #155724;
        }

        .draft {
            background: #fff3cd;
            color: #856404;
        }

        .edit {
            background: #28a745;
            color: white;
            padding: 7px 10px;
            text-decoration: none;
            border-radius: 4px;
        }

        .delete {
            background: #dc3545;
            color: white;
            padding: 7px 10px;
            text-decoration: none;
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



<?php include __DIR__ . '/../sidebar.php'; ?>


<div class="topbar">
    <h2>Admin Panel - Posts</h2>

    <a href="../logout.php">Logout</a>
</div>
        
<div class="container">

    <div class="header">
        <h1>Manage Posts</h1>

        <a href="create.php" class="btn">
            + Create New Post
        </a>
    </div>

    <div class="table-box">

        <table>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

            <?php if (mysqli_num_rows($result) > 0): ?>

                <?php while ($post = mysqli_fetch_assoc($result)): ?>

                    <tr>

                        <td>
                            <?php echo $post['id']; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($post['title']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($post['category_name'] ?? 'N/A'); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($post['author_name'] ?? 'N/A'); ?>
                        </td>

                        <td>

                            <?php if ($post['status'] === 'published'): ?>

                                <span class="status published">
                                    Published
                                </span>

                            <?php else: ?>

                                <span class="status draft">
                                    Draft
                                </span>

                            <?php endif; ?>

                        </td>

                        <td>
                            <?php echo date("M d, Y", strtotime($post['created_at'])); ?>
                        </td>

                        <td>

                            <a
                                href="edit.php?id=<?php echo $post['id']; ?>"
                                class="edit"
                            >
                                Edit
                            </a>

                            <a
                                href="delete.php?id=<?php echo $post['id']; ?>"
                                class="delete"
                                onclick="return confirm('Are you sure you want to delete this post?');"
                            >
                                Delete
                            </a>

                        </td>

                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>
                    <td colspan="7" class="empty">
                        No posts found.
                    </td>
                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>