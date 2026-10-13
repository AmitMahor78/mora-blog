
<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once "../../includes/db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$siteName = trim($_POST['site_name'] ?? '');
$description = trim($_POST['site_description'] ?? '');
$location = trim($_POST['location'] ?? '');
$workingDays = trim($_POST['working_days'] ?? '');
$closingDay = trim($_POST['closing_day'] ?? '');
$copyrightYear = trim($_POST['copyright_year'] ?? '');
$developerName = trim($_POST['developer_name'] ?? '');
$developerUrl = trim($_POST['developer_url'] ?? '');
$status = $_POST['status'] ?? '';

if ($siteName === '') {
    $_SESSION['settings_message'] = 'Website name is required.';
    header("Location: index.php");
    exit;
}

if (!in_array($status, ['active', 'inactive'], true)) {
    $_SESSION['settings_message'] = 'Invalid website status.';
    header("Location: index.php");
    exit;
}

if (
    $copyrightYear !== '' &&
    (!ctype_digit($copyrightYear) ||
     (int)$copyrightYear < 1900 ||
     (int)$copyrightYear > 2200)
) {
    $_SESSION['settings_message'] = 'Please enter a valid copyright year.';
    header("Location: index.php");
    exit;
}

if ($developerUrl !== '') {
    $urlParts = parse_url($developerUrl);

    if (
        !filter_var($developerUrl, FILTER_VALIDATE_URL) ||
        !in_array(strtolower($urlParts['scheme'] ?? ''), ['http', 'https'], true)
    ) {
        $_SESSION['settings_message'] = 'Please enter a valid HTTP or HTTPS developer URL.';
        header("Location: index.php");
        exit;
    }
}

$check = $conn->prepare("SELECT id FROM site_settings WHERE id = 1 LIMIT 1");
$check->execute();
$exists = $check->get_result()->fetch_assoc();
$check->close();

if (!$exists) {
    $_SESSION['settings_message'] = 'Settings record not found.';
    header("Location: index.php");
    exit;
}

$sql = "UPDATE site_settings
        SET site_name = ?,
            site_description = ?,
            location = ?,
            working_days = ?,
            closing_day = ?,
            copyright_year = ?,
            developer_name = ?,
            developer_url = ?,
            status = ?,
            updated_at = CURRENT_TIMESTAMP
        WHERE id = 1";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "sssssssss",
    $siteName,
    $description,
    $location,
    $workingDays,
    $closingDay,
    $copyrightYear,
    $developerName,
    $developerUrl,
    $status
);

if ($stmt->execute()) {
    $_SESSION['settings_message'] = 'Site settings updated successfully.';
} else {
    $_SESSION['settings_message'] = 'Unable to update settings. Please try again.';
}

$stmt->close();

header("Location: index.php");
exit;
