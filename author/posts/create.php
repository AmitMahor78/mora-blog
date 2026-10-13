<?php

session_start();

require_once "../../includes/db.php";
require_once "../../includes/functions.php";

// Login check
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Only author can access
if ($_SESSION['user_role'] !== 'author') {
    header("Location: ../../admin/dashboard.php");
    exit;
}

$authorId = (int) $_SESSION['user_id'];

$error = "";

// Fetch categories
$categoriesQuery = "
    SELECT id, name
    FROM categories
    ORDER BY name ASC
";

$categoriesResult = mysqli_query($conn, $categoriesQuery);


// Form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $categoryId = (int) ($_POST['category_id'] ?? 0);
    $status = $_POST['status'] ?? 'draft';

    // Basic validation
    if ($title === '' || $content === '' || $categoryId <= 0) {

        $error = "Please fill all required fields.";

    } else {

        // Validate status
        if (!in_array($status, ['draft', 'published'])) {
            $status = 'draft';
        }


        /*
        ==========================================
        IMAGE UPLOAD
        ==========================================
        */

        $imagePath = "";

        if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {

            // Upload error
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

                    // Create unique filename
                    $newFileName = uniqid('post_', true) . '.' . $fileExtension;

                    // Upload folder
                    $uploadDirectory = "../../uploads/posts/";

                    // Create folder if it doesn't exist
                    if (!is_dir($uploadDirectory)) {
                        mkdir($uploadDirectory, 0777, true);
                    }

                    // Final path
                    $uploadPath = $uploadDirectory . $newFileName;

                    // Database path
                    $imagePath = "uploads/posts/" . $newFileName;


                    // Move uploaded image
                    if (!move_uploaded_file($fileTmpName, $uploadPath)) {

                        $error = "Unable to upload image.";

                    }

                }

            }

        }


        /*
        ==========================================
        INSERT POST
        ==========================================
        */

        if ($error === '') {

            $slug = create_unique_slug($conn, 'posts', $title);
            $excerpt = generate_excerpt($content);

            $titleSafe = mysqli_real_escape_string($conn, $title);
            $slugSafe = mysqli_real_escape_string($conn, $slug);
            $excerptSafe = mysqli_real_escape_string($conn, $excerpt);
            $contentSafe = mysqli_real_escape_string($conn, $content);
            $imageSafe = mysqli_real_escape_string($conn, $imagePath);

            $insertQuery = "
                INSERT INTO posts
                (
                    title,
                    slug,
                    excerpt,
                    content,
                    category_id,
                    author_id,
                    image,
                    status
                )
                VALUES
                (
                    '$titleSafe',
                    '$slugSafe',
                    '$excerptSafe',
                    '$contentSafe',
                    $categoryId,
                    $authorId,
                    '$imageSafe',
                    '$status'
                )
            ";

            if (mysqli_query($conn, $insertQuery)) {

                header("Location: index.php?success=created");
                exit;

            } else {

                // If database insert fails, remove uploaded image
                if ($imagePath !== '') {

                    $uploadedFile = "../../" . $imagePath;

                    if (file_exists($uploadedFile)) {
                        unlink($uploadedFile);
                    }

                }

                $error = "Failed to create post: " . mysqli_error($conn);

            }

        }

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Post | Mora Blog</title>

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

        <h1>Create New Post</h1>


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

            <label>Post Title *</label>

            <input
                type="text"
                name="title"
                required
                value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>"
            >


            <!-- CATEGORY -->

            <label>Category *</label>

            <select name="category_id" required>

                <option value="">
                    Select Category
                </option>

                <?php while ($category = mysqli_fetch_assoc($categoriesResult)): ?>

                    <option
                        value="<?php echo $category['id']; ?>"
                        <?php
                        echo (
                            isset($_POST['category_id']) &&
                            $_POST['category_id'] == $category['id']
                        ) ? 'selected' : '';
                        ?>
                    >

                        <?php echo htmlspecialchars($category['name']); ?>

                    </option>

                <?php endwhile; ?>

            </select>


            <!-- IMAGE UPLOAD -->

            <label>Post Image</label>

            <input
                type="file"
                name="image"
                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
            >

            <div class="image-info">
                Allowed: JPG, JPEG, PNG, WEBP | Maximum size: 5 MB
            </div>


            <!-- CONTENT -->

            <label>Content *</label>

            <textarea
                name="content"
                required
            ><?php echo htmlspecialchars($_POST['content'] ?? ''); ?></textarea>


            <!-- STATUS -->

            <label>Status</label>

            <select name="status">

                <option value="draft">
                    Draft
                </option>

                <option value="published">
                    Published
                </option>

            </select>


            <!-- SUBMIT -->

            <button
                type="submit"
                class="btn"
            >
                Create Post
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