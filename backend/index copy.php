<?php
/**
 * Playing Cards Distribution API
 */

// Enable CORS,output format and preflight requests for frontend access
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle CORS preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Include the card distributor class
require_once __DIR__ . '/CardDistributor.php';

try {
    // Get input data from either POST or GET request
    // POST data should be JSON, GET data from query parameters
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $requestData = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON data provided');
        }
        $data = $requestData;
    } else {
        $data = $_GET;
    }
    
    // Validate input
    if (!isset($data['numberOfPeople'])) {
        throw new Exception('Input value does not exist or value is invalid');
    }
    
    // Convert to integer and validate
    $numberOfPeople = intval($data['numberOfPeople']);
    if ($numberOfPeople <= 0) {
        throw new Exception('Input value does not exist or value is invalid');
    }
    
    // Create distributor instance and get card distribution
    $distributor = new CardDistributor();
    $result = $distributor->distributeCards($numberOfPeople);
    
    // Return success response
    echo json_encode([
        'success' => true,
        'data' => $result
    ]);
    
} catch (Exception $e) {
    // Return error response with 400 status code
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
