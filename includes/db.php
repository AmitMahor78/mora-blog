<?php

$host = "sql305.infinityfree.com";
$user = "if0_43165092";
$password = "Amit7879";
$database = "if0_43165092_morablog";

$conn = mysqli_connect(
    $host,
    $user,
    $password,
    $database
);

if (!$conn) {
    error_log("Database connection failed.");
    http_response_code(500);
    exit("Website temporarily unavailable.");
}

mysqli_set_charset($conn, "utf8mb4");
?>