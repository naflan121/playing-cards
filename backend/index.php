<?php
// Enable CORS for frontend communication
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle CORS preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Read input JSON data
$input = json_decode(file_get_contents("php://input"), true);

// Validate input
if (!isset($input['numberOfPeople']) || !is_numeric($input['numberOfPeople']) || $input['numberOfPeople'] <= 0) {
    echo json_encode(["success" => false, "error" => "Input value does not exist or value is invalid"]);
    exit();
}

$numberOfPeople = (int)$input['numberOfPeople'];

// Define the deck of 52 cards
$suits = ['S', 'H', 'D', 'C'];
$values = ['A', '2', '3', '4', '5', '6', '7', '8', '9', 'X', 'J', 'Q', 'K'];
$deck = [];

foreach ($suits as $suit) {
    foreach ($values as $value) {
        $deck[] = "$suit-$value";
    }
}

// Shuffle the deck randomly
shuffle($deck);

// Distribute cards fairly among the given number of people
$distribution = array_fill(0, $numberOfPeople, []);

for ($i = 0; $i < count($deck); $i++) {
    $distribution[$i % $numberOfPeople][] = $deck[$i];
}

// Format output: Convert each person's cards into a comma-separated string
$output = [];
foreach ($distribution as $cards) {
    $output[] = implode(",", $cards);
}

// Respond with JSON output
echo json_encode(["success" => true, "data" => $output]);
?>
