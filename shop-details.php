<?php

include "includes/db.php";


/* =========================================================
   PRODUCT ID
========================================================= */

$productId = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;


/* =========================================================
   PRODUCT DETAILS
========================================================= */

$productQuery = "
    SELECT
        id,
        name,
        price,
        image,
        category,
        description,
        status,
        created_at
    FROM products
    WHERE id = $productId
      AND status = 'active'
    LIMIT 1
";

$productResult = mysqli_query($conn, $productQuery);


/* =========================================================
   CHECK PRODUCT
========================================================= */

if (!$productResult || mysqli_num_rows($productResult) === 0) {

    header("Location: shop.php");
    exit;

}

$product = mysqli_fetch_assoc($productResult);


/* =========================================================
   PRODUCT DATA
========================================================= */

$productName = $product['name'];

$productPrice = (float) $product['price'];

$productImage = $product['image'];

$productCategory = $product['category'];

$productDescription = $product['description'];


/* =========================================================
   PRODUCT DESCRIPTION
========================================================= */

if (empty($productDescription)) {

    $productDescription =
        "This product is currently available in our shop. "
        . "Please check the product details and price before purchasing.";

}


/* =========================================================
   SEARCH
========================================================= */

$search = isset($_GET['search'])
    ? trim($_GET['search'])
    : '';


/* =========================================================
   SHOP CATEGORIES
========================================================= */

$categoryQuery = "
    SELECT
        category,
        COUNT(*) AS total
    FROM products
    WHERE status = 'active'
    GROUP BY category
    ORDER BY category ASC
";

$categoryResult = mysqli_query($conn, $categoryQuery);


/* =========================================================
   POPULAR PRODUCTS
========================================================= */

$popularQuery = "
    SELECT
        id,
        name,
        price,
        image
    FROM products
    WHERE status = 'active'
      AND id != $productId
    ORDER BY id DESC
    LIMIT 4
";

$popularResult = mysqli_query($conn, $popularQuery);


/* =========================================================
   RELATED PRODUCTS
========================================================= */

$categorySafe = mysqli_real_escape_string(
    $conn,
    $productCategory
);

$relatedQuery = "
    SELECT
        id,
        name,
        price,
        image,
        category
    FROM products
    WHERE status = 'active'
      AND id != $productId
      AND category = '$categorySafe'
    ORDER BY id DESC
    LIMIT 3
";

$relatedResult = mysqli_query($conn, $relatedQuery);


/* =========================================================
   SOCIAL LINKS
========================================================= */

$socialQuery = "
    SELECT
        platform,
        url
    FROM social_links
    WHERE status = 'active'
    ORDER BY id ASC
";

$socialResult = mysqli_query($conn, $socialQuery);

$socialLinks = [];

if ($socialResult) {

    while ($row = mysqli_fetch_assoc($socialResult)) {

        $socialLinks[$row['platform']] = $row['url'];

    }

}

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <link
        rel="icon"
        href="images/favicon.png"
        type="image/x-icon"
    />

    <!-- Theme title -->

    <title>
        <?php echo htmlspecialchars($productName); ?>
        | Mora Shop
    </title>

    <!-- Theme style CSS -->

    <link
        href="css/style.css"
        rel="stylesheet"
    >

    <!-- HTML5 shim and Respond.js -->

    <!--[if lt IE 9]>

    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>

    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <![endif]-->

</head>


<body class="background-transparent">


<!-- =========================================================
     HEADER TOP AREA
========================================================= -->

<?php include "includes/header.php"; ?>


<!-- =========================================================
     HEADER / NAVBAR
========================================================= -->

<header
    class="main_header_area"
    id="header"
>

    <div class="container">

        <div class="header_menu">

            <?php include "includes/navbar.php"; ?>

        </div>

    </div>

</header>


<!-- =========================================================
     PAGE HEADER
========================================================= -->

<div class="container">

    <div class="pages_heder">

        <h2>
            Shop Details
        </h2>

        <ol class="breadcrumb">

            <li>
                <a href="index.php">
                    Home
                </a>
            </li>

            <li>
                <a href="shop.php">
                    Shop
                </a>
            </li>

            <li>
                <a
                    href="shop-details.php?id=<?php echo $productId; ?>"
                    class="active"
                >
                    Shop Details
                </a>
            </li>

        </ol>

    </div>

</div>


<!-- =========================================================
     SHOP BANNER AREA
========================================================= -->

<section class="shop_banner">

    <div class="container">

        <div class="row">


            <!-- Advertisement -->

            <a
                href="shop.php"
                class="col-12 add_aera"
            >

                <img
                    src="images/shop/add.jpg"
                    alt="Shop Advertisement"
                >

            </a>


            <!-- Offer 1 -->

            <div class="col-lg-3 col-md-6">

                <div class="slider_offer">

                    <a href="shop.php">
                        Free Shipping
                    </a>

                    <img
                        src="images/shop/shop-slider-bottom-1.jpg"
                        alt="Free Shipping"
                    >

                </div>

            </div>


            <!-- Offer 2 -->

            <div class="col-lg-3 col-md-6 order-lg-last">

                <div class="slider_offer">

                    <a href="shop.php">
                        Free Shipping
                    </a>

                    <img
                        src="images/shop/shop-slider-bottom-2.jpg"
                        alt="Free Shipping"
                    >

                </div>

            </div>


            <!-- Offer 3 -->

            <div class="col-lg-6">

                <div class="slider_offer">

                    <a
                        href="shop.php"
                        class="middle_offer"
                    >

                        55% OFF

                        <small>
                            First order
                        </small>

                    </a>

                    <img
                        src="images/shop/shop-slider-bottom-3.jpg"
                        alt="55% OFF"
                    >

                </div>

            </div>


        </div>

    </div>

</section>


<!-- =========================================================
     SHOP WITH SIDEBAR
========================================================= -->

<section class="shop_with_sidebar">

    <div class="container">

        <div class="row">


            <!-- =================================================
                 SIDEBAR
            ================================================= -->

            <div class="col-lg-3 shop_right_sidebar">


                <!-- Product Search -->

                <form
                    action="shop.php"
                    method="GET"
                >

                    <div class="input-group">

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search Product"
                            value="<?php echo htmlspecialchars($search); ?>"
                        >

                        <div class="input-group-append">

                            <button
                                type="submit"
                                class="input-group-text theme_btn"
                                style="border:none;"
                            >

                                <i class="fa fa-search"></i>

                            </button>

                        </div>

                    </div>

                </form>


                <!-- =================================================
                     SHOP CATEGORIES
                ================================================= -->

                <div class="s_widget">

                    <h4>
                        Shop Categories :
                    </h4>

                    <ul class="categories">


                        <!-- All Products -->

                        <li>

                            <a href="shop.php">

                                All Products

                            </a>

                        </li>


                        <?php

                        if ($categoryResult) {

                            while (
                                $category =
                                mysqli_fetch_assoc($categoryResult)
                            ) {

                        ?>

                            <li>

                                <a
                                    href="shop.php?category=<?php echo urlencode($category['category']); ?>"
                                >

                                    <?php

                                    echo htmlspecialchars(
                                        $category['category']
                                    );

                                    ?>

                                    (<?php
                                    echo $category['total'];
                                    ?>)

                                </a>

                            </li>

                        <?php

                            }

                        }

                        ?>

                    </ul>

                </div>


                <!-- =================================================
                     POPULAR PRODUCTS
                ================================================= -->

                <div class="s_widget popular_products">

                    <h4>
                        Popular Product
                    </h4>


                    <?php

                    if (
                        $popularResult &&
                        mysqli_num_rows($popularResult) > 0
                    ) {

                        $popularFirst = true;

                        while (
                            $popular =
                            mysqli_fetch_assoc($popularResult)
                        ) {

                    ?>

                        <div
                            class="media
                            <?php
                            echo $popularFirst
                                ? 'media_fast'
                                : '';
                            ?>"
                        >


                            <a
                                href="shop-details.php?id=<?php echo $popular['id']; ?>"
                            >

                                <img
                                    src="<?php echo htmlspecialchars($popular['image']); ?>"
                                    alt="<?php echo htmlspecialchars($popular['name']); ?>"
                                >

                            </a>


                            <div class="media-body">

                                <a
                                    href="shop-details.php?id=<?php echo $popular['id']; ?>"
                                >

                                    <?php

                                    echo htmlspecialchars(
                                        $popular['name']
                                    );

                                    ?>

                                </a>


                                <h6>

                                    $

                                    <?php

                                    echo number_format(
                                        $popular['price'],
                                        2
                                    );

                                    ?>

                                </h6>

                            </div>


                        </div>


                    <?php

                            $popularFirst = false;

                        }

                    } else {

                    ?>

                        <p>
                            No popular products found.
                        </p>

                    <?php

                    }

                    ?>

                </div>


            </div>


            <!-- =================================================
                 PRODUCT DETAILS
            ================================================= -->

            <div class="col-lg-9 shop_left_sidebar">

                <div class="row">


                    <!-- Product Image -->

                    <div class="col-md-6 min_img">

                        <img
                            src="<?php echo htmlspecialchars($productImage); ?>"
                            alt="<?php echo htmlspecialchars($productName); ?>"
                        >


                        <!-- Product Size -->

                        <ul class="product_size">

                            <li>
                                Size :
                            </li>

                            <li class="active">
                                S
                            </li>

                            <li>
                                M
                            </li>

                            <li>
                                L
                            </li>

                            <li>
                                XL
                            </li>

                            <li>
                                XXL
                            </li>

                        </ul>

                    </div>


                    <!-- Product Information -->

                    <div class="col-md-6 product_details">


                        <!-- Product Name -->

                        <h2>

                            <?php

                            echo htmlspecialchars(
                                $productName
                            );

                            ?>

                        </h2>


                        <!-- Price -->

                        <h1>

                            $

                            <?php

                            echo number_format(
                                $productPrice,
                                2
                            );

                            ?>


                            <small>
                                In stock
                            </small>

                        </h1>


                        <!-- Category -->

                        <h4>

                            Categories:

                            <?php

                            echo htmlspecialchars(
                                $productCategory
                            );

                            ?>

                        </h4>


                        <!-- Rating -->

                        <ul class="rating">

                            <li>

                                <a href="#">
                                    <i class="fa fa-star"></i>
                                </a>

                                <a href="#">
                                    <i class="fa fa-star"></i>
                                </a>

                                <a href="#">
                                    <i class="fa fa-star"></i>
                                </a>

                                <a href="#">
                                    <i class="fa fa-star"></i>
                                </a>

                                <a href="#">
                                    <i class="fa fa-star-half-o"></i>
                                </a>

                            </li>

                        </ul>


                        <!-- Description -->

                        <p>

                            <?php

                            echo nl2br(
                                htmlspecialchars(
                                    $productDescription
                                )
                            );

                            ?>

                        </p>


                        <!-- Quantity -->

                        <ul class="value-section">


                            <li>

                                <button
                                    type="button"
                                    class="tran3s"
                                    id="value-decrease"
                                >

                                    <i
                                        class="fa fa-angle-left"
                                        aria-hidden="true"
                                    ></i>

                                </button>

                            </li>


                            <li id="product-value">
                                1
                            </li>


                            <li>

                                <button
                                    type="button"
                                    class="tran3s"
                                    id="value-increase"
                                >

                                    <i
                                        class="fa fa-angle-right"
                                        aria-hidden="true"
                                    ></i>

                                </button>

                            </li>


                            <li>

                                <a
                                    href="#"
                                    class="theme_btn"
                                    onclick="return false;"
                                >
                                    Add to cart
                                </a>

                            </li>


                        </ul>


                    </div>


                    <!-- =================================================
                         REVIEW / DESCRIPTION TABS
                    ================================================= -->

                    <div class="review-tab col-12">


                        <ul class="nav nav-tabs">

                            <li>

                                <a
                                    data-toggle="tab"
                                    href="#home"
                                    class="active theme_btn"
                                >

                                    Description

                                </a>

                            </li>


                            <li>

                                <a
                                    data-toggle="tab"
                                    href="#menu1"
                                    class="theme_btn"
                                >

                                    Additional Information

                                </a>

                            </li>


                            <li>

                                <a
                                    data-toggle="tab"
                                    href="#menu2"
                                    class="theme_btn"
                                >

                                    Reviews (0)

                                </a>

                            </li>

                        </ul>


                        <div class="tab-content">


                            <!-- Description -->

                            <div
                                id="home"
                                class="tab-pane fade in active show"
                            >

                                <p>

                                    <?php

                                    echo nl2br(
                                        htmlspecialchars(
                                            $productDescription
                                        )
                                    );

                                    ?>

                                </p>

                            </div>


                            <!-- Additional Information -->

                            <div
                                id="menu1"
                                class="tab-pane fade"
                            >

                                <p>

                                    <strong>
                                        Product Name:
                                    </strong>

                                    <?php

                                    echo htmlspecialchars(
                                        $productName
                                    );

                                    ?>

                                </p>


                                <p>

                                    <strong>
                                        Category:
                                    </strong>

                                    <?php

                                    echo htmlspecialchars(
                                        $productCategory
                                    );

                                    ?>

                                </p>


                                <p>

                                    <strong>
                                        Price:
                                    </strong>

                                    $

                                    <?php

                                    echo number_format(
                                        $productPrice,
                                        2
                                    );

                                    ?>

                                </p>


                                <p>

                                    <strong>
                                        Availability:
                                    </strong>

                                    In Stock

                                </p>

                            </div>


                            <!-- Reviews -->

                            <div
                                id="menu2"
                                class="tab-pane fade"
                            >

                                <p>
                                    No reviews available for this
                                    product yet.
                                </p>

                            </div>


                        </div>

                    </div>


                    <!-- =================================================
                         RELATED PRODUCTS HEADING
                    ================================================= -->

                    <div class="col-12">

                        <h2 class="r_heding">
                            Related Products
                        </h2>

                    </div>


                    <!-- =================================================
                         RELATED PRODUCTS
                    ================================================= -->

                    <?php

                    if (
                        $relatedResult &&
                        mysqli_num_rows($relatedResult) > 0
                    ) {

                        while (
                            $related =
                            mysqli_fetch_assoc($relatedResult)
                        ) {

                    ?>


                        <div class="col-md-4 col-sm-6">


                            <div class="shop_items">


                                <!-- Related Product Image -->

                                <a
                                    href="shop-details.php?id=<?php echo $related['id']; ?>"
                                    class="shop_img"
                                >

                                    <img
                                        src="<?php echo htmlspecialchars($related['image']); ?>"
                                        alt="<?php echo htmlspecialchars($related['name']); ?>"
                                    >

                                </a>


                                <!-- Related Product Details -->

                                <div class="shop_text">


                                    <a
                                        href="shop-details.php?id=<?php echo $related['id']; ?>"
                                        class="s_heding"
                                    >

                                        <?php

                                        echo htmlspecialchars(
                                            $related['name']
                                        );

                                        ?>

                                    </a>


                                    <h4>

                                        $

                                        <?php

                                        echo number_format(
                                            $related['price'],
                                            2
                                        );

                                        ?>

                                    </h4>


                                    <a
                                        href="shop-details.php?id=<?php echo $related['id']; ?>"
                                        class="theme_btn"
                                    >

                                        View Details

                                    </a>


                                </div>


                            </div>


                        </div>


                    <?php

                        }

                    } else {

                    ?>


                        <div class="col-12">

                            <p>
                                No related products found.
                            </p>

                        </div>


                    <?php

                    }

                    ?>


                </div>

            </div>


        </div>

    </div>

</section>


<!-- =========================================================
     CONNECT WITH US
========================================================= -->

<section class="connect_with_us">

    <div class="container">

        <h2>
            Connect with us
        </h2>


        <ul class="contact_with_socail">


            <?php if (!empty($socialLinks['google'])) { ?>

                <li>

                    <a
                        href="<?php echo htmlspecialchars($socialLinks['google']); ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                    >

                        <i class="fa fa-google-plus"></i>

                    </a>

                </li>

            <?php } ?>


            <?php if (!empty($socialLinks['linkedin'])) { ?>

                <li>

                    <a
                        href="<?php echo htmlspecialchars($socialLinks['linkedin']); ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                    >

                        <i class="fa fa-linkedin"></i>

                    </a>

                </li>

            <?php } ?>


            <?php if (!empty($socialLinks['instagram'])) { ?>

                <li>

                    <a
                        href="<?php echo htmlspecialchars($socialLinks['instagram']); ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                    >

                        <i class="fa fa-instagram"></i>

                    </a>

                </li>

            <?php } ?>


            <?php if (!empty($socialLinks['twitter'])) { ?>

                <li>

                    <a
                        href="<?php echo htmlspecialchars($socialLinks['twitter']); ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                    >

                        <i class="fa fa-twitter"></i>

                    </a>

                </li>

            <?php } ?>


            <?php if (!empty($socialLinks['youtube'])) { ?>

                <li>

                    <a
                        href="<?php echo htmlspecialchars($socialLinks['youtube']); ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                    >

                        <i class="fa fa-youtube"></i>

                    </a>

                </li>

            <?php } ?>


        </ul>

    </div>

</section>


<!-- =========================================================
     FOOTER
========================================================= -->

<?php include "includes/footer.php"; ?>


<!-- =========================================================
     SCROLL TOP
========================================================= -->

<button class="scroll-top">

    <i class="fa fa-angle-double-up"></i>

</button>


<!-- =========================================================
     PRELOADER
========================================================= -->

<div class="preloader"></div>


<!-- =========================================================
     SCRIPTS
========================================================= -->

<script src="js/jquery-3.3.1.min.js"></script>

<script src="js/popper.min.js"></script>

<script src="js/bootstrap.min.js"></script>

<script src="vendors/animate-css/wow.min.js"></script>

<script src="js/theme.js"></script>


<!-- =========================================================
     PRODUCT QUANTITY
========================================================= -->

<script>

    document.addEventListener("DOMContentLoaded", function () {

        let quantity = 1;

        const decreaseButton =
            document.getElementById("value-decrease");

        const increaseButton =
            document.getElementById("value-increase");

        const productValue =
            document.getElementById("product-value");


        if (decreaseButton) {

            decreaseButton.addEventListener(
                "click",
                function () {

                    if (quantity > 1) {

                        quantity--;

                        productValue.innerText =
                            quantity;

                    }

                }
            );

        }


        if (increaseButton) {

            increaseButton.addEventListener(
                "click",
                function () {

                    quantity++;

                    productValue.innerText =
                        quantity;

                }
            );

        }

    });

</script>


</body>

</html>