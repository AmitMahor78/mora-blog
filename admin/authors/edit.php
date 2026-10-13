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
    Fetch author
*/
$query = "
    SELECT
        id,
        name,
        email
    FROM users
    WHERE id = $authorId
    AND role = 'author'
    LIMIT 1
";

$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    header("Location: index.php");
    exit;
}

$author = mysqli_fetch_assoc($result);

$error = "";


/*
    Update author
*/
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];


    if ($name === "" || $email === "") {

        $error = "Name and email are required.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please enter a valid email address.";

    } else {

        $nameSafe = mysqli_real_escape_string($conn, $name);
        $emailSafe = mysqli_real_escape_string($conn, $email);


        /*
            Check duplicate email
        */
        $checkQuery = "
            SELECT id
            FROM users
            WHERE email = '$emailSafe'
            AND id != $authorId
            LIMIT 1
        ";

        $checkResult = mysqli_query($conn, $checkQuery);


        if (mysqli_num_rows($checkResult) > 0) {

            $error = "Another user is already using this email.";

        } else {

            /*
                Update name and email
            */
            $updateQuery = "
                UPDATE users
                SET
                    name = '$nameSafe',
                    email = '$emailSafe'
                WHERE id = $authorId
                AND role = 'author'
            ";


            if (mysqli_query($conn, $updateQuery)) {

                /*
                    Update password only when entered
                */
                if ($password !== "") {

                    if (strlen($password) < 6) {

                        $error = "Password must contain at least 6 characters.";

                    } else {

                        $hashedPassword = password_hash(
                            $password,
                            PASSWORD_DEFAULT
                        );

                        $passwordSafe = mysqli_real_escape_string(
                            $conn,
                            $hashedPassword
                        );

                        $passwordQuery = "
                            UPDATE users
                            SET password = '$passwordSafe'
                            WHERE id = $authorId
                            AND role = 'author'
                        ";

                        if (!mysqli_query($conn, $passwordQuery)) {

                            $error = "Author updated but password update failed.";
                        }
                    }
                }


                if ($error === "") {

                    header("Location: index.php?success=updated");
                    exit;
                }

            } else {

                $error = "Failed to update author.";
            }
        }
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

    <title>Edit Author - Admin Panel</title>

    <style>

        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            background: #f4f6f9;
        }

        .topbar {
            background: #222;
            color: white;
            padding: 18px 30px;
            display: flex;
            justify-content: space-between;
        }

        .topbar a {
            color: white;
            text-decoration: none;
            background: #dc3545;
            padding: 8px 14px;
            border-radius: 5px;
        }

        .container {
            max-width: 650px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .08);
        }

        h1 {
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .back {
            margin-left: 10px;
            color: #333;
            text-decoration: none;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .note {
            margin-top: 5px;
            color: #777;
            font-size: 13px;
        }

    </style>

</head>

<body>

<div class="topbar">

    <strong>Admin Panel</strong>

    <a href="../logout.php">
        Logout
    </a>

</div>


<div class="container">

    <h1>Edit Author</h1>


    <?php if ($error !== ""): ?>

        <div class="error">

            <?php echo htmlspecialchars($error); ?>

        </div>

    <?php endif; ?>


    <form method="POST">

        <div class="form-group">

            <label>
                Author Name
            </label>

            <input
                type="text"
                name="name"
                value="<?php echo htmlspecialchars($author['name']); ?>"
                required
            >

        </div>


        <div class="form-group">

            <label>
                Email
            </label>

            <input
                type="email"
                name="email"
                value="<?php echo htmlspecialchars($author['email']); ?>"
                required
            >

        </div>


        <div class="form-group">

            <label>
                New Password
            </label>

            <input
                type="password"
                name="password"
                placeholder="Leave blank to keep current password"
            >

            <div class="note">
                Enter a new password only if you want to change it.
                Minimum 6 characters.
            </div>

        </div>


        <button
            type="submit"
            class="btn"
        >
            Update Author
        </button>


        <a
            href="index.php"
            class="back"
        >
            Cancel
        </a>

    </form>

</div>

</body>

</html>