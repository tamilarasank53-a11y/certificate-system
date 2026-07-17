<?php
// Handle certificate download

if (!isset($_GET['id'])) {
    http_response_code(400);
    die('Certificate ID not provided');
}

$cert_id = $_GET['id'];

// Database configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'kpr_college';

// Connect to database
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    http_response_code(500);
    die('Database connection failed');
}

// Get certificate path
$query = "SELECT c.certificate_path, c.id FROM certificates c WHERE c.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $cert_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    die('Certificate not found');
}

$cert = $result->fetch_assoc();
$filepath = $cert['certificate_path'];

if (!file_exists($filepath)) {
    http_response_code(404);
    die('File not found');
}

// Log download
$ip = $_SERVER['REMOTE_ADDR'];
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$log_query = "INSERT INTO download_logs (certificate_id, ip_address, user_agent) VALUES (?, ?, ?)";
$log_stmt = $conn->prepare($log_query);
$log_stmt->bind_param("iss", $cert['id'], $ip, $user_agent);
$log_stmt->execute();

// Update download count
$update_query = "UPDATE certificates SET download_count = download_count + 1 WHERE id = ?";
$update_stmt = $conn->prepare($update_query);
$update_stmt->bind_param("i", $cert['id']);
$update_stmt->execute();

// Download file
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
header('Content-Length: ' . filesize($filepath));
readfile($filepath);

$conn->close();
exit();
?>
