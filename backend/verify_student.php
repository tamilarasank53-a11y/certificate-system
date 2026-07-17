<?php
header('Content-Type: application/json');

// Enable error reporting
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Database configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'kpr_college';

// Connect to database
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed'
    ]);
    exit();
}

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);

$email = trim($input['email'] ?? '');
$name = trim($input['name'] ?? '');
$rollno = trim($input['rollno'] ?? '');

// Validate inputs
if (empty($email) || empty($name) || empty($rollno)) {
    echo json_encode([
        'success' => false,
        'message' => 'All fields are required'
    ]);
    exit();
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid email format'
    ]);
    exit();
}

// Check if student is registered
$query = "SELECT * FROM students WHERE email = ? AND rollno = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $email, $rollno);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Student not found in our database. Please verify your email and roll number.'
    ]);
    exit();
}

$student = $result->fetch_assoc();

// Check if certificate already generated
$cert_query = "SELECT certificate_path FROM certificates WHERE student_id = ?";
$cert_stmt = $conn->prepare($cert_query);
$cert_stmt->bind_param("i", $student['id']);
$cert_stmt->execute();
$cert_result = $cert_stmt->get_result();

if ($cert_result->num_rows > 0) {
    $cert = $cert_result->fetch_assoc();
    $cert_path = $cert['certificate_path'];
} else {
    // Generate certificate
    require_once 'generate_certificate.php';
    
    $cert_path = generateCertificate($name, $student['id']);
    
    if (!$cert_path) {
        echo json_encode([
            'success' => false,
            'message' => 'Error generating certificate'
        ]);
        exit();
    }

    // Save to database
    $insert_query = "INSERT INTO certificates (student_id, certificate_path, created_at) VALUES (?, ?, NOW())";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("is", $student['id'], $cert_path);
    $insert_stmt->execute();
}

// Send email with download link
require_once 'send_email.php';

$certificate_url = 'https://yoursite.com/certificates/' . basename($cert_path);

if (sendCertificateEmail($email, $name, $certificate_url)) {
    echo json_encode([
        'success' => true,
        'message' => 'Certificate sent to your email successfully!',
        'certificate_link' => $cert_path
    ]);
} else {
    echo json_encode([
        'success' => true,
        'message' => 'Certificate generated successfully!',
        'certificate_link' => $cert_path
    ]);
}

$conn->close();
?>
