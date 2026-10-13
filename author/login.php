<?php

session_start();

include "../includes/db.php";

// If already logged in
if (isset($_SESSION['user_id'])) {

    if ($_SESSION['user_role'] === 'author') {
        header("Location: dashboard.php");
        exit;
    }

    if ($_SESSION['user_role'] === 'admin') {
        header("Location: ../admin/dashboard.php");
        exit;
    }
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === "" || $password === "") {

        $error = "Please enter email and password.";

    } else {

        $emailSafe = mysqli_real_escape_string($conn, $email);

        $query = "
            SELECT
                id,
                name,
                email,
                password,
                role
            FROM users
            WHERE email = '$emailSafe'
            LIMIT 1
        ";

        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) === 1) {

            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user['password'])) {

                // Only Author can use Author Login
                if ($user['role'] !== 'author') {

                    $error = "Access denied. This login is only for authors.";

                } else {

                    // Regenerate session ID
                    session_regenerate_id(true);

                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_role'] = $user['role'];

                    header("Location: dashboard.php");
                    exit;
                }

            } else {

                $error = "Invalid email or password.";

            }

        } else {

            $error = "Invalid email or password.";

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

    <title>Author Login | Mora</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            min-height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login_container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .login_box {
            background: #ffffff;
            padding: 35px;
            border-radius: 8px;

            box-shadow:
                0 5px 25px rgba(0, 0, 0, 0.10);
        }

        .login_box h2 {
            text-align: center;
            margin-bottom: 8px;
            color: #222;
        }

        .subtitle {
            text-align: center;
            color: #777;
            margin-bottom: 25px;
        }

        .form_group {
            margin-bottom: 18px;
        }

        .form_group label {
            display: block;
            margin-bottom: 7px;
            font-weight: 600;
            color: #333;
        }

        .form_group input {
            width: 100%;
            padding: 12px;

            border: 1px solid #ddd;
            border-radius: 5px;

            outline: none;
        }

        .form_group input:focus {
            border-color: #007bff;
        }

        .login_btn {
            width: 100%;

            padding: 12px;

            border: none;
            border-radius: 5px;

            background: #007bff;
            color: #fff;

            font-size: 16px;

            cursor: pointer;
        }

        .login_btn:hover {
            background: #0056b3;
        }

        .error_message {
            background: #ffe5e5;
            color: #c00;

            padding: 10px;
            margin-bottom: 18px;

            border-radius: 5px;

            text-align: center;
        }

        .back_home {
            text-align: center;
            margin-top: 20px;
        }

        .back_home a {
            color: #007bff;
            text-decoration: none;
        }

    </style>

</head>

<body>

<div class="login_container">

    <div class="login_box">

        <h2>Mora CMS</h2>

        <p class="subtitle">
            Author Login
        </p>

        <?php if ($error !== "") { ?>

            <div class="error_message">
                <?php echo htmlspecialchars($error); ?>
            </div>

        <?php } ?>


        <form method="POST" action="">

            <div class="form_group">

                <label for="email">
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter your email"
                    value="<?php echo htmlspecialchars($email ?? ''); ?>"
                    required
                >

            </div>


            <div class="form_group">

                <label for="password">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                    required
                >

            </div>


            <button
                type="submit"
                class="login_btn"
            >
                Author Login
            </button>

        </form>


        <div class="back_home">

            <a href="../index.php">
                ← Back to Website
            </a>

        </div>

    </div>

</div>

</body>

</html>