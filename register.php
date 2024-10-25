<?php
header('Content-Type: application/json');

$servername = 'localhost';
$username = 'myaccount';
$password = 'abc123***ABC';
$dbname = 'regdata';
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'DB connection failed: ' . $conn->connect_error]));
}

$data = json_decode(file_get_contents('php://input'), true);
$user = $data['username'] ?? null;
$pass = $data['password'] ?? null;
$role = $data['role'] ?? null;

$errorMessage = null;

if (strlen($pass) < 8) {
    $errorMessage = 'Password length must be greater than 8.';
} else if (!preg_match('/\d/', $pass)) {
    $errorMessage = 'Password must contain at least 1 digit.';
} else if (!preg_match('/[A-Z]/', $pass)) {
    $errorMessage = 'Password must contain at least 1 uppercase letter.';
} else if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $pass)) {
    $errorMessage = 'Password must contain at least 1 special character.';
}

// If all validations pass, continue with registration
$hashed_password = password_hash($pass, PASSWORD_BCRYPT);
$stmt = $conn->prepare('INSERT INTO register_table(username, password, role) VALUES(?,?,?)');
$stmt->bind_param('sss', $user, $hashed_password, $role);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Registration Success.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>

