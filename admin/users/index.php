
<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once "../../includes/db.php";

$query = "SELECT id, name, email, status, created_at
          FROM registered_users
          ORDER BY id DESC";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Unable to fetch registered users.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Users | Mora Blog</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            color: #333;
        }

        .topbar {
            margin-left: 260px;
            padding: 20px 25px;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.08);
        }

        .topbar h2 { margin: 0; font-size: 23px; }

        .container {
            margin-left: 260px;
            padding: 25px;
        }

        .page-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
        }

        .page-heading h3 { margin: 0; }

        .table-wrapper {
            overflow-x: auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        table {
            width: 100%;
            min-width: 750px;
            border-collapse: collapse;
        }

        th, td {
            padding: 13px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #212529;
            color: #fff;
        }

        tr:hover { background: #f8f9fa; }

        .status {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
        }

        .active {
            background: #d1e7dd;
            color: #0f5132;
        }

        .inactive {
            background: #f8d7da;
            color: #842029;
        }

        .btn {
            display: inline-block;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            color: #fff;
            text-decoration: none;
            cursor: pointer;
            font-size: 13px;
        }

        .btn-edit { background: #0d6efd; }
        .btn-delete { background: #dc3545; }

        .actions {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .actions form { margin: 0; }

        .message {
            padding: 12px;
            margin-bottom: 18px;
            border-radius: 5px;
            background: #d1e7dd;
            color: #0f5132;
        }

        .empty {
            text-align: center;
            padding: 25px;
        }

        @media (max-width: 768px) {
            .topbar, .container { margin-left: 220px; }
        }

        @media (max-width: 480px) {
            .topbar, .container {
                margin-left: 200px;
                padding: 15px 10px;
            }
        }
    </style>
</head>
<body>

<?php include_once __DIR__ . '/../sidebar.php'; ?>

<header class="topbar">
    <h2>Registered Users Management</h2>
</header>

<main class="container">

    <div class="page-heading">
        <h3>Website Registered Users</h3>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="message">
            <?php
            $messages = [
                'updated' => 'User status updated successfully.',
                'deleted' => 'Registered user deleted successfully.'
            ];

            $key = $_GET['success'];

            echo htmlspecialchars(
                $messages[$key] ?? 'Operation completed successfully.'
            );
            ?>
        </div>
    <?php endif; ?>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Registered At</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>

                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= (int)$row['id']; ?></td>

                        <td><?= htmlspecialchars($row['name']); ?></td>

                        <td><?= htmlspecialchars($row['email']); ?></td>

                        <td>
                            <span class="status <?= $row['status'] === 'active' ? 'active' : 'inactive'; ?>">
                                <?= htmlspecialchars(ucfirst($row['status'])); ?>
                            </span>
                        </td>

                        <td><?= htmlspecialchars($row['created_at']); ?></td>

                        <td>
                            <div class="actions">
                                <a
                                    class="btn btn-edit"
                                    href="edit.php?id=<?= (int)$row['id']; ?>"
                                >Edit Status</a>

                                <form
                                    action="delete.php"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this registered user?');"
                                >
                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= (int)$row['id']; ?>"
                                    >
                                    <button type="submit" class="btn btn-delete">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>

            <?php else: ?>
                <tr>
                    <td colspan="6" class="empty">
                        No registered users found.
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

</main>
</body>
</html>
