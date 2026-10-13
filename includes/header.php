<?php

include __DIR__ . "/db.php";


// =====================================================
// 1. FETCH SITE SETTINGS
// =====================================================

$settingsQuery = "
    SELECT
        site_name
    FROM site_settings
    WHERE status = 'active'
    ORDER BY id DESC
    LIMIT 1
";

$settingsResult = mysqli_query($conn, $settingsQuery);

$siteName = "Mora";

if ($settingsResult && mysqli_num_rows($settingsResult) > 0) {

    $settings = mysqli_fetch_assoc($settingsResult);

    if (!empty($settings['site_name'])) {
        $siteName = $settings['site_name'];
    }
}


// =====================================================
// 2. FETCH SOCIAL LINKS
// =====================================================

$socialQuery = "
    SELECT platform, url
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

<!-- =====================================================
     HEADER TOP AREA
===================================================== -->

<div class="header_top_area">

    <div class="container">

        <div class="header_top_inner d-sm-flex justify-content-sm-between">


            <!-- =================================================
                 LEFT MENU
            ================================================== -->

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


            <!-- =================================================
                 RIGHT SOCIAL MENU
            ================================================== -->

            <ul class="header_social">


                <!-- Facebook -->

                <?php if (!empty($socialLinks['facebook'])) { ?>

                    <li>

                        <a
                            href="<?php echo htmlspecialchars($socialLinks['facebook']); ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                        >

                            <i class="fa fa-facebook"></i>

                        </a>

                    </li>

                <?php } ?>


                <!-- Twitter -->

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


                <!-- LinkedIn -->

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


                <!-- Pinterest -->

                <?php if (!empty($socialLinks['pinterest'])) { ?>

                    <li>

                        <a
                            href="<?php echo htmlspecialchars($socialLinks['pinterest']); ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                        >

                            <i class="fa fa-pinterest"></i>

                        </a>

                    </li>

                <?php } ?>


                <!-- Google -->

                <?php if (!empty($socialLinks['google'])) { ?>

                    <li>

                        <a
                            href="<?php echo htmlspecialchars($socialLinks['google']); ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                        >

                            <i class="fa fa-google"></i>

                        </a>

                    </li>

                <?php } ?>


                <!-- Instagram -->

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


                <!-- Shopping Bag -->

                <li>

                    <a
                        href="#"
                        class="shop_btn"
                    >

                        <i class="flaticon-shopping-bag"></i>

                        <span>0</span>

                    </a>

                </li>


                <!-- Get Free Quote -->

                <li>

                    <a
                        href="#"
                        class="quote_btn"
                    >

                        Get Free Quote

                        <i class="fa fa-arrow-right"></i>

                    </a>

                </li>


            </ul>

        </div>

    </div>

</div>