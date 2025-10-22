<?php
/**
 * API Endpoint - Generate Username
 */

header('Content-Type: application/json');
require_once '../includes/Auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$firstName = trim($input['first_name'] ?? '');
$lastName = trim($input['last_name'] ?? '');
$dob = $input['date_of_birth'] ?? '';

if (empty($firstName) || empty($lastName) || empty($dob)) {
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

try {
    $auth = new Auth();
    $username = $auth->generateUsername($firstName, $lastName, $dob);
    
    echo json_encode([
        'success' => true,
        'username' => $username
    ]);
} catch (Exception $e) {
    error_log("Username generation error: " . $e->getMessage());
    echo json_encode(['error' => 'Failed to generate username']);
}
