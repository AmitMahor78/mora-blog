<?php

session_start();

require_once "../../includes/db.php";
require_once "../../includes/functions.php";

// Login check
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Only author
if ($_SESSION['user_role'] !== 'author') {
    header("Location: ../../admin/dashboard.php");
    exit;
}

$authorId = (int) $_SESSION['user_id'];

$postId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($postId <= 0) {
    header("Location: index.php");
    exit;
}


// Fetch only this author's post
$postQuery = "
    SELECT
        id,
        title,
        content,
        category_id,
        image,
        status
    FROM posts
    WHERE id = $postId
    AND author_id = $authorId
    LIMIT 1
";

$postResult = mysqli_query($conn, $postQuery);

if (!$postResult || mysqli_num_rows($postResult) === 0) {
    die("Post not found or you are not authorized to edit this post.");
}

$post = mysqli_fetch_assoc($postResult);

$error = "";


// Fetch categories
$categoriesQuery = "
    SELECT id, name
    FROM categories
    ORDER BY name ASC
";

$categoriesResult = mysqli_query($conn, $categoriesQuery);


// Update post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $categoryId = (int) ($_POST['category_id'] ?? 0);
    $status = $_POST['status'] ?? 'draft';

    if ($title === '' || $content === '' || $categoryId <= 0) {

        $error = "Please fill all required fields.";

    } else {

        // Validate status
        if (!in_array($status, ['draft', 'published'])) {
            $status = 'draft';
        }


        /*
        ==========================================
        CURRENT IMAGE
        ==========================================
        */

        $oldImage = $post['image'] ?? '';

        $imagePath = $oldImage;


        /*
        ==========================================
        NEW IMAGE UPLOAD
        ==========================================
        */

        if (
            isset($_FILES['image']) &&
            $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE
        ) {

            if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {

                $error = "Image upload failed.";

            } else {

                $fileName = $_FILES['image']['name'];
                $fileTmpName = $_FILES['image']['tmp_name'];
                $fileSize = $_FILES['image']['size'];

                // Get extension
                $fileExtension = strtolower(
                    pathinfo($fileName, PATHINFO_EXTENSION)
                );

                // Allowed extensions
                $allowedExtensions = [
                    'jpg',
                    'jpeg',
                    'png',
                    'webp'
                ];


                // Check extension
                if (!in_array($fileExtension, $allowedExtensions)) {

                    $error = "Only JPG, JPEG, PNG and WEBP images are allowed.";

                }

                // Maximum 5 MB
                elseif ($fileSize > 5 * 1024 * 1024) {

                    $error = "Image size must be less than 5 MB.";

                }

                else {

                    // Upload directory
                    $uploadDirectory = "../../uploads/posts/";

                    // Create folder if not exists
                    if (!is_dir($uploadDirectory)) {
                        mkdir($uploadDirectory, 0777, true);
                    }

                    // Create unique filename
                    $newFileName = uniqid(
                        'post_',
                        true
                    ) . '.' . $fileExtension;

                    // Server path
                    $uploadPath = $uploadDirectory . $newFileName;

                    // Database path
                    $newImagePath = "uploads/posts/" . $newFileName;


                    // Move uploaded file
                    if (move_uploaded_file(
                        $fileTmpName,
                        $uploadPath
                    )) {

                        $imagePath = $newImagePath;

                    } else {

                        $error = "Unable to upload image.";

                    }

                }

            }

        }


        /*
        ==========================================
        UPDATE DATABASE
        ==========================================
        */

        if ($error === '') {

            $slug = !empty($post['slug']) ? $post['slug'] : create_unique_slug($conn, 'posts', $title, $postId);
            $excerpt = generate_excerpt($content);

            $titleSafe = mysqli_real_escape_string(
                $conn,
                $title
            );

            $slugSafe = mysqli_real_escape_string(
                $conn,
                $slug
            );

            $excerptSafe = mysqli_real_escape_string(
                $conn,
                $excerpt
            );

            $contentSafe = mysqli_real_escape_string(
                $conn,
                $content
            );

            $imageSafe = mysqli_real_escape_string(
                $conn,
                $imagePath
            );


            $updateQuery = "
                UPDATE posts
                SET
                    title = '$titleSafe',
                    slug = '$slugSafe',
                    excerpt = '$excerptSafe',
                    content = '$contentSafe',
                    category_id = $categoryId,
                    image = '$imageSafe',
                    status = '$status'
                WHERE id = $postId
                AND author_id = $authorId
            ";


            if (mysqli_query($conn, $updateQuery)) {

                /*
                ==========================================
                DELETE OLD IMAGE
                ONLY IF NEW IMAGE WAS UPLOADED
                ==========================================
                */

                if (
                    isset($newImagePath) &&
                    $newImagePath !== '' &&
                    $oldImage !== '' &&
                    strpos($oldImage, 'uploads/posts/') === 0
                ) {

                    $oldImageFile = "../../" . $oldImage;

                    if (
                        file_exists($oldImageFile) &&
                        is_file($oldImageFile)
                    ) {

                        unlink($oldImageFile);

                    }

                }


                header("Location: index.php?success=updated");
                exit;

            } else {

                /*
                If database update fails,
                remove newly uploaded image.
                */

                if (
                    isset($newImagePath) &&
                    $newImagePath !== ''
                ) {

                    $newUploadedFile = "../../" . $newImagePath;

                    if (
                        file_exists($newUploadedFile) &&
                        is_file($newUploadedFile)
                    ) {

                        unlink($newUploadedFile);

                    }

                }

                $error = "Failed to update post: " . mysqli_error($conn);

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

    <title>Edit Post | Mora Blog</title>


    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f6fa;
        }

        .main {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .box {
            background: white;
            padding: 30px;
            border-radius: 10px;
        }

        h1 {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 15px;
        }

        textarea {
            min-height: 250px;
            resize: vertical;
        }

        input[type="file"] {
            padding: 10px;
            background: #fafafa;
        }

        .image-info {
            margin-top: -12px;
            margin-bottom: 20px;
            color: #777;
            font-size: 13px;
        }

        .current-image {
            margin-bottom: 20px;
        }

        .current-image img {
            display: block;
            width: 180px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
            margin-top: 10px;
        }

        .no-image {
            color: #777;
            font-size: 14px;
            margin-top: 10px;
        }

        .btn {
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            color: white;
            background: #2563eb;
            font-size: 15px;
        }

        .back {
            text-decoration: none;
            color: #555;
            margin-left: 15px;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 6px;
        }

    </style>

</head>


<body>


<div class="main">

    <div class="box">

        <h1>Edit Post</h1>


        <?php if ($error !== ''): ?>

            <div class="error">

                <?php echo htmlspecialchars($error); ?>

            </div>

        <?php endif; ?>


        <form
            method="POST"
            enctype="multipart/form-data"
        >


            <!-- TITLE -->

            <label>
                Post Title *
            </label>

            <input
                type="text"
                name="title"
                required
                value="<?php echo htmlspecialchars($post['title']); ?>"
            >


            <!-- CATEGORY -->

            <label>
                Category *
            </label>

            <select
                name="category_id"
                required
            >

                <option value="">
                    Select Category
                </option>


                <?php while ($category = mysqli_fetch_assoc($categoriesResult)): ?>

                    <option
                        value="<?php echo $category['id']; ?>"
                        <?php
                        echo (
                            $post['category_id'] == $category['id']
                        )
                            ? 'selected'
                            : '';
                        ?>
                    >

                        <?php
                        echo htmlspecialchars(
                            $category['name']
                        );
                        ?>

                    </option>

                <?php endwhile; ?>

            </select>


            <!-- CURRENT IMAGE -->

            <div class="current-image">

                <label>
                    Current Image
                </label>


                <?php if (!empty($post['image'])): ?>

                    <img
                        src="../../<?php echo htmlspecialchars($post['image']); ?>"
                        alt="Current Post Image"
                    >

                <?php else: ?>

                    <div class="no-image">
                        No image available.
                    </div>

                <?php endif; ?>

            </div>


            <!-- NEW IMAGE -->

            <label>
                Replace Image
            </label>

            <input
                type="file"
                name="image"
                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
            >

            <div class="image-info">
                Leave empty to keep the current image.
                <br>
                Allowed: JPG, JPEG, PNG, WEBP | Maximum size: 5 MB
            </div>


            <!-- CONTENT -->

            <label>
                Content *
            </label>

            <textarea
                name="content"
                required
            ><?php echo htmlspecialchars($post['content']); ?></textarea>


            <!-- STATUS -->

            <label>
                Status
            </label>

            <select name="status">

                <option
                    value="draft"
                    <?php
                    echo $post['status'] === 'draft'
                        ? 'selected'
                        : '';
                    ?>
                >
                    Draft
                </option>

                <option
                    value="published"
                    <?php
                    echo $post['status'] === 'published'
                        ? 'selected'
                        : '';
                    ?>
                >
                    Published
                </option>

            </select>


            <!-- BUTTON -->

            <button
                type="submit"
                class="btn"
            >
                Update Post
            </button>


            <a
                href="index.php"
                class="back"
            >
                Back to Posts
            </a>

        </form>

    </div>

</div>


</body>

</html>