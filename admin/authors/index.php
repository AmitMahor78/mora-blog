<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

include "../../includes/db.php";


/*
    Fetch all authors with their post count
*/
$query = "
    SELECT
        u.id,
        u.name,
        u.email,
        u.created_at,
        COUNT(p.id) AS post_count
    FROM users u
    LEFT JOIN posts p
        ON u.id = p.author_id
    WHERE u.role = 'author'
    GROUP BY
        u.id,
        u.name,
        u.email,
        u.created_at
    ORDER BY u.created_at DESC
";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Database Error: " . mysqli_error($conn));
}


$message = "";
$messageType = "";


/*
    Success messages
*/
if (isset($_GET['success'])) {

    if ($_GET['success'] === 'created') {
        $message = "Author created successfully.";
        $messageType = "success";
    }

    if ($_GET['success'] === 'updated') {
        $message = "Author updated successfully.";
        $messageType = "success";
    }

    if ($_GET['success'] === 'deleted') {
        $message = "Author deleted successfully.";
        $messageType = "success";
    }
}


/*
    Error messages
*/
if (isset($_GET['error'])) {

    if ($_GET['error'] === 'has_posts') {
        $message = "This author has posts and cannot be deleted.";
        $messageType = "error";
    }

    if ($_GET['error'] === 'delete_failed') {
        $message = "Failed to delete author.";
        $messageType = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Manage Authors - Admin Panel</title>

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
            background: #dc3545;
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

        .add-btn {
            background: #007bff;
            color: white;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 5px;
        }

        .add-btn:hover {
            background: #0056b3;
        }

        .message {
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .success {
            background: #d4edda;
            color: #155724;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
        }

        .table-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .08);
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

        .count {
            display: inline-block;
            background: #e9ecef;
            padding: 5px 10px;
            border-radius: 15px;
        }

        .edit-btn {
            background: #28a745;
            color: white;
            text-decoration: none;
            padding: 7px 10px;
            border-radius: 4px;
        }

        .delete-btn {
            background: #dc3545;
            color: white;
            text-decoration: none;
            padding: 7px 10px;
            border-radius: 4px;
        }

        .disabled-btn {
            background: #999;
            color: white;
            padding: 7px 10px;
            border-radius: 4px;
            cursor: not-allowed;
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

    <h2>Admin Panel - Authors</h2>

    <a href="../logout.php">
        Logout
    </a>

</div>


<div class="container">

    <div class="header">

        <h1>Manage Authors</h1>

        <a
            href="create.php"
            class="add-btn"
        >
            + Add Author
        </a>

    </div>


    <?php if ($message !== ""): ?>

        <div class="message <?php echo $messageType; ?>">

            <?php echo htmlspecialchars($message); ?>

        </div>

    <?php endif; ?>


    <div class="table-box">

        <table>

            <thead>

                <tr>

                    <th>ID</th>

                    <th>Name</th>

                    <th>Email</th>

                    <th>Posts</th>

                    <th>Created</th>

                    <th>Actions</th>

                </tr>

            </thead>

            <tbody>

            <?php if (mysqli_num_rows($result) > 0): ?>

                <?php while ($author = mysqli_fetch_assoc($result)): ?>

                    <tr>

                        <td>
                            <?php echo $author['id']; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($author['name']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($author['email']); ?>
                        </td>

                        <td>

                            <span class="count">

                                <?php echo $author['post_count']; ?>

                                <?php
                                echo ($author['post_count'] == 1)
                                    ? ' Post'
                                    : ' Posts';
                                ?>

                            </span>

                        </td>

                        <td>
                            <?php
                            echo date(
                                "M d, Y",
                                strtotime($author['created_at'])
                            );
                            ?>
                        </td>

                        <td>

                            <a
                                href="edit.php?id=<?php echo $author['id']; ?>"
                                class="edit-btn"
                            >
                                Edit
                            </a>


                            <?php if ($author['post_count'] == 0): ?>

                                <a
                                    href="delete.php?id=<?php echo $author['id']; ?>"
                                    class="delete-btn"
                                    onclick="return confirm('Are you sure you want to delete this author?');"
                                >
                                    Delete
                                </a>

                            <?php else: ?>

                                <span class="disabled-btn">
                                    Delete
                                </span>

                            <?php endif; ?>

                        </td>

                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>

                    <td
                        colspan="6"
                        class="empty"
                    >
                        No authors found.
                    </td>

                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

</body>

</html>