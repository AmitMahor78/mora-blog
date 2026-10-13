
<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once "../../includes/db.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header("Location: index.php");
    exit;
}

$stmt = mysqli_prepare(
    $conn,
    "SELECT id, name, email, status
     FROM registered_users
     WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$user) {
    header("Location: index.php");
    exit;
}

$status = $user['status'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'] ?? '';

    if (!in_array($status, ['active', 'inactive'], true)) {
        $error = "Please select a valid status.";
    } else {
        $updateStmt = mysqli_prepare(
            $conn,
            "UPDATE registered_users SET status = ? WHERE id = ?"
        );

        if ($updateStmt) {
            mysqli_stmt_bind_param($updateStmt, "si", $status, $id);

            if (mysqli_stmt_execute($updateStmt)) {
                mysqli_stmt_close($updateStmt);
                header("Location: index.php?success=updated");
                exit;
            }

            $error = "Unable to update user status.";
            mysqli_stmt_close($updateStmt);
        } else {
            $error = "Unable to prepare the database query.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Registered User | Mora Blog</title>

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

        .form-card {
            max-width: 650px;
            padding: 25px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .form-group { margin-bottom: 20px; }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            padding: 11px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 15px;
        }

        input[readonly] {
            background: #e9ecef;
            color: #555;
        }

        .btn {
            display: inline-block;
            padding: 10px 16px;
            border: none;
            border-radius: 5px;
            color: #fff;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-save { background: #0d6efd; }
        .btn-back { background: #6c757d; }

        .error {
            padding: 12px;
            margin-bottom: 18px;
            border-radius: 5px;
            background: #f8d7da;
            color: #842029;
        }

        @media (max-width: 768px) {
            .topbar, .container { margin-left: 220px; }
        }

        @media (max-width: 480px) {
            .topbar, .container {
                margin-left: 200px;
                padding: 15px 10px;
            }

            .form-card { padding: 18px; }
        }
    </style>
</head>
<body>

<?php include_once __DIR__ . '/../sidebar.php'; ?>

<header class="topbar">
    <h2>Edit Registered User</h2>
</header>

<main class="container">
    <div class="form-card">
        <h3>Update User Status</h3>

        <?php if ($error !== ''): ?>
            <div class="error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="edit.php?id=<?= (int)$id; ?>" method="POST">

            <div class="form-group">
                <label for="name">Name</label>
                <input
                    type="text"
                    id="name"
                    value="<?= htmlspecialchars($user['name']); ?>"
                    readonly
                >
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    value="<?= htmlspecialchars($user['email']); ?>"
                    readonly
                >
            </div>

            <div class="form-group">
                <label for="status">Account Status</label>
                <select id="status" name="status" required>
                    <option value="active" <?= $status === 'active' ? 'selected' : ''; ?>>
                        Active
                    </option>
                    <option value="inactive" <?= $status === 'inactive' ? 'selected' : ''; ?>>
                        Inactive
                    </option>
                </select>
            </div>

            <button type="submit" class="btn btn-save">Update Status</button>
            <a href="index.php" class="btn btn-back">Cancel</a>

        </form>
    </div>
</main>
</body>
</html>
