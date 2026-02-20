<?php
// Handle POST data and save to FlipClock10Events.json
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['events'])) {
    // Verify password before processing
    if (!isset($_POST['password'])) {
        http_response_code(403);
        echo 'Password required.';
        exit;
    }
    
    // Load password hash from .env file
    $envFile = $_SERVER['HOME'] . '/.env';
    $passwordHash = '';
    if (file_exists($envFile)) {
        $envContent = file_get_contents($envFile);
        if (preg_match('/^FLIPCLOCKPASSWORD=(.+)$/m', $envContent, $matches)) {
            $passwordHash = trim($matches[1]);
        }
    }
    
    // Verify password
    if (empty($passwordHash) || !password_verify($_POST['password'], $passwordHash)) {
        http_response_code(403);
        echo 'Invalid password.';
        exit;
    }
    
    $events = [];
    
    // Process the events array from POST data
    foreach ($_POST['events'] as $event) {
        if (isset($event['label']) && isset($event['targetDate'])) {
            $eventData = [
                'label' => trim($event['label']),
                'targetDate' => trim($event['targetDate']),
                'pinned' => isset($event['pinned']) && $event['pinned'] === '1'
            ];
            
            // Only add if label and targetDate are not empty
            if (!empty($eventData['label']) && !empty($eventData['targetDate'])) {
                $events[] = $eventData;
            }
        }
    }
    
    // Save to JSON file
    $jsonFile = __DIR__ . '/FlipClock10Events.json';
    $jsonData = json_encode($events, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
    if (file_put_contents($jsonFile, $jsonData) !== false) {
        // Redirect back to FlipClock10.html
        header('Location: FlipClock.html');
        exit;
    } else {
        // Error saving file
        http_response_code(500);
        echo 'Error saving events file.';
        exit;
    }
} else {
    // If not POST request, redirect to FlipClock10.html
    header('Location: FlipClock.html');
    exit;
}
?>

