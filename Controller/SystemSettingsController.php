<?php
session_start();

require_once '/../Models/Database.php';
require_once '/../Models/Settings.php';

$admin_id = (int) ($_POST['admin_id'] ?? 0);
$settings = $_POST['settings'] ?? []; // array of ['key' => 'value']

if ($admin_id && !empty($settings)) {
    $Settings = new Settings();

    foreach ($settings as $key => $value) {
        $Settings->Update(htmlspecialchars($key), htmlspecialchars($value));
    }

    $_SESSION['settings_message'] = "Settings saved successfully!";
    header("Location: /SystemSettings.php?status=success");
} else {
    header("Location: /SystemSettings.php?error=missing_data");
}
exit();