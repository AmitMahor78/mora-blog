
<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once "../../includes/db.php";

$stmt = $conn->prepare("SELECT * FROM site_settings WHERE id = 1 LIMIT 1");
$stmt->execute();
$settings = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$settings) {
    die("Site settings record not found. Please check your site_settings table.");
}

function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

$message = $_SESSION['settings_message'] ?? '';
unset($_SESSION['settings_message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Settings</title>

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
            border-bottom: 1px solid #ddd;
        }

        .container {
            margin-left: 260px;
            padding: 25px;
        }

        .panel {
            max-width: 950px;
            padding: 25px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px #0000000d;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .full { grid-column: 1 / -1; }

        label { font-weight: 600; font-size: 14px; }

        input, textarea, select {
            width: 100%;
            padding: 11px;
            border: 1px solid #ccd0d5;
            border-radius: 5px;
            font: inherit;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        .hint {
            color: #777;
            font-size: 12px;
        }

        .btn {
            display: inline-block;
            margin-top: 22px;
            padding: 12px 20px;
            border: 0;
            border-radius: 5px;
            background: #2563eb;
            color: #fff;
            cursor: pointer;
            font-size: 14px;
        }

        .notice {
            padding: 12px 15px;
            margin-bottom: 20px;
            background: #dcfce7;
            color: #166534;
            border-radius: 5px;
        }

        @media (max-width: 768px) {
            .topbar, .container { margin-left: 220px; }
            .form-grid { grid-template-columns: 1fr; }
            .full { grid-column: auto; }
        }

        @media (max-width: 480px) {
            .topbar, .container {
                margin-left: 200px;
                padding: 15px 10px;
            }

            .panel { padding: 15px; }
        }
    </style>
</head>
<body>

<?php include_once __DIR__ . '/../sidebar.php'; ?>

<div class="topbar">
    <h2>Site Settings</h2>
    <p>Manage your website's general information and footer details.</p>
</div>

<div class="container">
    <div class="panel">

        <?php if ($message !== ''): ?>
            <div class="notice"><?= e($message) ?></div>
        <?php endif; ?>

        <form action="update.php" method="POST">
            <input type="hidden" name="id" value="<?= (int)$settings['id'] ?>">

            <div class="form-grid">

                <div class="field">
                    <label for="site_name">Website Name *</label>
                    <input
                        type="text"
                        id="site_name"
                        name="site_name"
                        maxlength="150"
                        required
                        value="<?= e($settings['site_name']) ?>"
                    >
                </div>

                <div class="field">
                    <label for="status">Website Status *</label>
                    <select id="status" name="status" required>
                        <option value="active" <?= $settings['status'] === 'active' ? 'selected' : '' ?>>
                            Active
                        </option>
                        <option value="inactive" <?= $settings['status'] === 'inactive' ? 'selected' : '' ?>>
                            Inactive
                        </option>
                    </select>
                    <span class="hint">Inactive status only changes the database until the frontend checks it.</span>
                </div>

                <div class="field full">
                    <label for="site_description">Website Description</label>
                    <textarea
                        id="site_description"
                        name="site_description"
                        maxlength="5000"
                    ><?= e($settings['site_description']) ?></textarea>
                </div>

                <div class="field full">
                    <label for="location">Location / Address</label>
                    <input
                        type="text"
                        id="location"
                        name="location"
                        maxlength="255"
                        value="<?= e($settings['location']) ?>"
                    >
                </div>

                <div class="field">
                    <label for="working_days">Working Days</label>
                    <input
                        type="text"
                        id="working_days"
                        name="working_days"
                        maxlength="100"
                        value="<?= e($settings['working_days']) ?>"
                    >
                </div>

                <div class="field">
                    <label for="closing_day">Closing Day</label>
                    <input
                        type="text"
                        id="closing_day"
                        name="closing_day"
                        maxlength="100"
                        value="<?= e($settings['closing_day']) ?>"
                    >
                </div>

                <div class="field">
                    <label for="copyright_year">Copyright Year</label>
                    <input
                        type="number"
                        id="copyright_year"
                        name="copyright_year"
                        min="1900"
                        max="2200"
                        value="<?= e($settings['copyright_year']) ?>"
                    >
                </div>

                <div class="field">
                    <label for="developer_name">Developer Name</label>
                    <input
                        type="text"
                        id="developer_name"
                        name="developer_name"
                        maxlength="150"
                        value="<?= e($settings['developer_name']) ?>"
                    >
                </div>

                <div class="field full">
                    <label for="developer_url">Developer Website URL</label>
                    <input
                        type="url"
                        id="developer_url"
                        name="developer_url"
                        maxlength="500"
                        placeholder="https://example.com"
                        value="<?= e($settings['developer_url']) ?>"
                    >
                </div>

            </div>

            <button type="submit" class="btn">Save Settings</button>
        </form>
    </div>
</div>

</body>
</html>
