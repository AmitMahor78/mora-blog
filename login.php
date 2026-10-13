<?php

session_start();

include "includes/db.php";

$message = "";
$messageType = "";


/*
|--------------------------------------------------------------------------
| Registration Success Message
|--------------------------------------------------------------------------
*/

if (isset($_GET['status']) && $_GET['status'] === 'success') {

    $message = "Registration successful. Please login.";
    $messageType = "success";

}


/*
|--------------------------------------------------------------------------
| Login Process
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';


    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    if ($email === '' || $password === '') {

        $message = "Please enter your email and password.";
        $messageType = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $messageType = "error";

    } else {


        /*
        |--------------------------------------------------------------------------
        | Find User From registered_users
        |--------------------------------------------------------------------------
        */

        $sql = "
            SELECT id, name, email, password, status
            FROM registered_users
            WHERE email = ?
            LIMIT 1
        ";

        $stmt = mysqli_prepare($conn, $sql);


        if ($stmt) {

            mysqli_stmt_bind_param(
                $stmt,
                "s",
                $email
            );

            mysqli_stmt_execute($stmt);

            mysqli_stmt_store_result($stmt);


            /*
            |--------------------------------------------------------------------------
            | Check User Exists
            |--------------------------------------------------------------------------
            */

            if (mysqli_stmt_num_rows($stmt) === 1) {

                mysqli_stmt_bind_result(
                    $stmt,
                    $userId,
                    $userName,
                    $userEmail,
                    $hashedPassword,
                    $userStatus
                );

                mysqli_stmt_fetch($stmt);


                /*
                |--------------------------------------------------------------------------
                | Check Account Status
                |--------------------------------------------------------------------------
                */

                if ($userStatus !== 'active') {

                    $message = "Your account is inactive. Please contact administrator.";
                    $messageType = "error";

                }


                /*
                |--------------------------------------------------------------------------
                | Verify Password
                |--------------------------------------------------------------------------
                */

                elseif (password_verify($password, $hashedPassword)) {


                    /*
                    |--------------------------------------------------------------------------
                    | Secure Session
                    |--------------------------------------------------------------------------
                    */

                    session_regenerate_id(true);


                    /*
                    |--------------------------------------------------------------------------
                    | Store User Information
                    |--------------------------------------------------------------------------
                    */

                    $_SESSION['registered_user_id'] = $userId;

                    $_SESSION['registered_user_name'] = $userName;

                    $_SESSION['registered_user_email'] = $userEmail;


                    mysqli_stmt_close($stmt);


                    /*
                    |--------------------------------------------------------------------------
                    | Login Successful
                    |--------------------------------------------------------------------------
                    */

                    header("Location: index.php");

                    exit;

                } else {

                    $message = "Invalid email or password.";
                    $messageType = "error";

                }

            } else {

                $message = "Invalid email or password.";
                $messageType = "error";

            }


            mysqli_stmt_close($stmt);

        } else {

            $message = "Database error. Please try again.";
            $messageType = "error";

        }

    }

}

?>

<!doctype html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">


    <link rel="icon"
          href="images/favicon.png"
          type="image/x-icon" />


    <!-- Theme title -->

    <title>Mora Blog | Login</title>


    <!-- Theme style CSS -->

    <link href="css/style.css" rel="stylesheet">


    <!-- HTML5 shim and Respond.js for IE8 support -->

    <!--[if lt IE 9]>

    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>

    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <![endif]-->

</head>


<body>


<!-- =========================================================
     Header Top Area
========================================================= -->

<div class="header_top_area">

    <div class="container">

        <div class="header_top_inner d-sm-flex justify-content-sm-between">


            <ul class="left_info">

                <li>
                    <a href="index.php">
                        Home
                    </a>
                </li>

                <li>
                    <a href="contact.php">
                        Contact Us
                    </a>
                </li>

                <li>
                    <a href="about.html">
                        About Us
                    </a>
                </li>

                <li>
                    <a href="error.php">
                        Error 404
                    </a>
                </li>

                <li>
                    <a href="login.php">
                        Login
                    </a>
                </li>

            </ul>


            <ul class="header_social">

                <li>
                    <a href="#">
                        <i class="fa fa-facebook"></i>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <i class="fa fa-twitter"></i>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <i class="fa fa-linkedin"></i>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <i class="fa fa-vine"></i>
                    </a>
                </li>


                <li>

                    <a href="#"
                       class="shop_btn">

                        <i class="flaticon-shopping-bag"></i>

                        <span>0</span>

                    </a>

                </li>


                <li>

                    <a href="#"
                       class="quote_btn">

                        Get Free Quote

                        <i class="fa fa-arrow-right"></i>

                    </a>

                </li>

            </ul>

        </div>

    </div>

</div>


<!-- =========================================================
     Header Area
========================================================= -->

<header class="main_header_area"
        id="header">

    <div class="container">

        <div class="header_menu">

            <nav class="navbar navbar-expand-lg">


                <a class="navbar-brand"
                   href="index.php">

                    <img src="images/logo.png"
                         alt="">

                </a>


                <!-- Small Device Menu -->

                <button class="navbar-toggler"
                        type="button"
                        data-toggle="collapse"
                        data-target=".navbar_supported"
                        aria-expanded="false"
                        aria-label="Toggle navigation">

                    <i class="fa fa-bars"
                       aria-hidden="true"></i>

                </button>


                <div class="collapse navbar-collapse navbar_supported">

                    <ul class="navbar-nav">


                        <!-- Home -->

                        <li class="dropdown">

                            <a class="nav-link dropdown-toggle"
                               href=""
                               role="button"
                               data-toggle="dropdown"
                               aria-expanded="false">

                                Home

                            </a>


                            <ul class="dropdown-menu">

                                <li>
                                    <a href="index.php"
                                       class="active">

                                        Home Version 1

                                    </a>
                                </li>

                                <li>
                                    <a href="index-2.php">

                                        Home Version 2

                                    </a>
                                </li>

                            </ul>

                        </li>


                        <!-- About -->

                        <li>

                            <a class="nav-link"
                               href="about.html">

                                About us

                            </a>

                        </li>


                        <!-- Blog -->

                        <li class="dropdown">

                            <a class="nav-link dropdown-toggle"
                               href=""
                               role="button"
                               data-toggle="dropdown"
                               aria-expanded="false">

                                BLog

                            </a>


                            <ul class="dropdown-menu">

                                <li>
                                    <a href="news.php">
                                        News v1
                                    </a>
                                </li>

                                <li>
                                    <a href="news-2.html">
                                        News v2
                                    </a>
                                </li>

                                <li>
                                    <a href="news-3.php">
                                        News v3
                                    </a>
                                </li>

                                <li>
                                    <a href="news-details.php">
                                        News details
                                    </a>
                                </li>

                                <li>
                                    <a href="video-details.html">
                                        Video details
                                    </a>
                                </li>

                                <li>
                                    <a href="news-details-2.php">
                                        News details v2
                                    </a>
                                </li>

                                <li>
                                    <a href="news-details-3.php">
                                        News details v3
                                    </a>
                                </li>

                            </ul>

                        </li>


                        <!-- Shop -->

                        <li class="dropdown">

                            <a class="nav-link dropdown-toggle"
                               href=""
                               role="button"
                               data-toggle="dropdown"
                               aria-expanded="false">

                                Shop

                            </a>


                            <ul class="dropdown-menu">

                                <li>
                                    <a href="shop.php">
                                        Shop
                                    </a>
                                </li>

                                <li>
                                    <a href="shop-details.php">
                                        Shop Details
                                    </a>
                                </li>

                            </ul>

                        </li>


                        <!-- Page -->

                        <li class="dropdown">

                            <a class="nav-link dropdown-toggle"
                               href=""
                               role="button"
                               data-toggle="dropdown"
                               aria-expanded="false">

                                Page

                            </a>


                            <ul class="dropdown-menu">


                                <li class="dropdown">

                                    <a class="nav-link dropdown-toggle"
                                       href="#"
                                       id="navbarDropdown"
                                       role="button"
                                       data-toggle="dropdown"
                                       aria-expanded="false">

                                        Sub Menu

                                    </a>


                                    <ul class="dropdown-menu"
                                        aria-labelledby="navbarDropdown">

                                        <li>
                                            <a href="#"
                                               class="active">
                                                -Sub Menu v1
                                            </a>
                                        </li>

                                        <li>
                                            <a href="#">
                                                -Sub Menu v2
                                            </a>
                                        </li>

                                        <li>
                                            <a href="#">
                                                -Sub Menu v3
                                            </a>
                                        </li>

                                    </ul>

                                </li>


                                <li>

                                    <a href="register.php">
                                        Register
                                    </a>

                                </li>


                                <li>

                                    <a href="login.php">
                                        Login
                                    </a>

                                </li>


                                <li>

                                    <a href="error.php">
                                        Error (404)
                                    </a>

                                </li>

                            </ul>

                        </li>


                        <!-- Contact -->

                        <li>

                            <a class="nav-link"
                               href="contact.php">

                                Contact us

                            </a>

                        </li>


                        <!-- Search -->

                        <li class="search_box">

                            <div class="input-group">

                                <input type="text"
                                       class="form-control"
                                       placeholder="Type and hit enter">


                                <div class="input-group-prepend">

                                    <span class="input-group-text">

                                        <i class="fa fa-search"></i>

                                    </span>

                                </div>

                            </div>

                        </li>


                    </ul>

                </div>

            </nav>


            <ul class="search_button_content nav">

                <li class="dropdown search_dropbown">

                    <a class="nav-link dropdown-toggle"
                       href=""
                       role="button"
                       data-toggle="dropdown"
                       aria-expanded="false">

                        <i class="fa fa-search"></i>

                    </a>


                    <ul class="dropdown-menu">

                        <li>

                            <input type="text"
                                   placeholder="Search..">

                            <span>

                                <i class="fa fa-search"></i>

                            </span>

                        </li>

                    </ul>

                </li>

            </ul>


        </div>

    </div>

</header>


<!-- =========================================================
     Pages Header Area
========================================================= -->

<div class="container">

    <div class="pages_heder">

        <h2>
            Login Page
        </h2>


        <ol class="breadcrumb">

            <li>
                <a href="index.php">
                    Home
                </a>
            </li>

            <li>
                <a href="">
                    Pages
                </a>
            </li>

            <li>
                <a href="login.php"
                   class="active">

                    Login Page

                </a>
            </li>

        </ol>

    </div>

</div>


<!-- =========================================================
     Login Area
========================================================= -->

<section class="login_area">

    <div class="container">

        <div class="login_inner">


            <!-- Login Message -->

            <?php if ($message !== '') { ?>

                <div style="
                    width: 100%;
                    padding: 12px 15px;
                    margin-bottom: 20px;
                    text-align: center;
                    border-radius: 3px;
                    <?php
                    echo ($messageType === 'success')
                        ? 'background:#d4edda;color:#155724;'
                        : 'background:#f8d7da;color:#721c24;';
                    ?>
                ">

                    <?php echo htmlspecialchars($message); ?>

                </div>

            <?php } ?>


            <!-- Login Form -->

            <form class="row login_from"
                  action="login.php"
                  method="POST">


                <div class="form-group col-12">

                    <input
                        type="email"
                        class="form-control"
                        name="email"
                        placeholder="Email Address"
                        value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                        required
                    >

                </div>


                <div class="form-group col-12">

                    <input
                        type="password"
                        class="form-control"
                        name="password"
                        placeholder="Password"
                        required
                    >

                </div>


                <!--
                    2FA is not implemented yet.
                    This field is kept only to preserve the original design.
                -->

                <div class="form-group col-12">

                    <input
                        type="text"
                        class="form-control"
                        name="code"
                        placeholder="Your Code ( 2fa if inavailed )"
                    >

                </div>


                <div class="form-group larg_btn col-12">

                    <button
                        class="defult_btn"
                        type="submit">

                        Login

                    </button>

                </div>


                <div class="form-group col-sm-6">

                    <a href="#"
                       class="reset_btn">

                        Reset Password

                    </a>

                </div>


                <div class="form-group col-sm-6">

                    <a href="register.php"
                       class="reset_btn">

                        Sign Up

                    </a>

                </div>


                <h4 class="col-12">
                    or login in with
                </h4>


                <div class="form-group col-sm-6">

                    <a href="#"
                       class="google_btn">

                        <img src="images/google.png"
                             alt="">

                        GOOGLE

                    </a>

                </div>


                <div class="form-group col-sm-6">

                    <a href="#"
                       class="google_btn">

                        <i class="fa fa-facebook"></i>

                        Sign Up

                    </a>

                </div>


                <div class="col-12">

                    <div class="custom-control custom-checkbox">

                        <input
                            type="checkbox"
                            class="custom-control-input"
                            id="check1"
                            required
                        >


                        <label
                            class="custom-control-label"
                            for="check1">

                            I agree to the mora.com

                            <a href="#">
                                Terms of Service
                            </a>

                        </label>

                    </div>

                </div>


            </form>

        </div>

    </div>

</section>


<!-- =========================================================
     Connect With Us
========================================================= -->

<section class="connect_with_us">

    <div class="container">

        <h2>
            Connect with us
        </h2>


        <ul class="contact_with_socail">

            <li>
                <a href="#">
                    <i class="fa fa-google-plus"></i>
                </a>
            </li>

            <li>
                <a href="#">
                    <i class="fa fa-linkedin"></i>
                </a>
            </li>

            <li>
                <a href="#">
                    <i class="fa fa-instagram"></i>
                </a>
            </li>

            <li>
                <a href="#">
                    <i class="fa fa-twitter"></i>
                </a>
            </li>

            <li>
                <a href="#">
                    <i class="fa fa-youtube"></i>
                </a>
            </li>

        </ul>

    </div>

</section>


<!-- =========================================================
     Footer Area
========================================================= -->

<footer class="footer_area">

    <div class="container">

        <div class="footer_inner row">


            <div class="col-lg-5 col-md-6 footer_logo">

                <a href="index.php">

                    <img src="images/logo.png"
                         alt="">

                </a>


                <p>

                    Established in 2013 as the first cloud mining
                    provider, Blog has become a multi-functional
                    Blog Categories, trusted by over a million users.

                </p>


                <address>

                    <span>
                        LOCATION
                    </span>

                    <p>
                        12/4 New Yourk R Block Street 2101 USA
                    </p>

                </address>

            </div>



            <div class="col-lg-5 col-md-6">

                <div class="subscribe">

                    <h4>
                        Subscribe
                    </h4>


                    <p>
                        Sign up for our mailing list to get latest
                        updates and offers
                    </p>


                    <div class="input-group">

                        <input
                            type="text"
                            class="form-control"
                            placeholder=""
                        >


                        <div class="input-group-append">

                            <span class="input-group-text">

                                <i class="fa fa-paper-plane"></i>

                            </span>

                        </div>

                    </div>


                    <h6>

                        Working Hours :
                        Monday-Saturday

                        <span>
                            Close : Sunday
                        </span>

                    </h6>

                </div>

            </div>



            <div class="col-lg-2 col-md-3 resources">

                <h4>
                    Short Link
                </h4>


                <ul class="resources_list">

                    <li>
                        <a href="about.html">
                            About
                        </a>
                    </li>

                    <li>
                        <a href="contact.php">
                            Contact
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            Bug Bounty Program
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            Policy
                        </a>
                    </li>

                    <li>
                        <a href="faq.html">
                            FAQ
                        </a>
                    </li>

                    <li>
                        <a href="shop.php">
                            Shop
                        </a>
                    </li>

                </ul>

            </div>


        </div>

    </div>


    <div class="copy_right">

        <p>

            Copyright © 2018

            <a href="#">
                Mora
            </a>

            Developed by

            <a href="http://themazin.com">
                Themazin
            </a>

        </p>

    </div>

</footer>


<!-- =========================================================
     Scroll Top Button
========================================================= -->

<button class="scroll-top">

    <i class="fa fa-angle-double-up"></i>

</button>


<!-- =========================================================
     Preloader
========================================================= -->

<div class="preloader"></div>


<!-- =========================================================
     JavaScript
========================================================= -->

<script src="js/jquery-3.3.1.min.js"></script>

<script src="js/popper.min.js"></script>

<script src="js/bootstrap.min.js"></script>

<script src="vendors/animate-css/wow.min.js"></script>

<script src="js/theme.js"></script>


</body>

</html>