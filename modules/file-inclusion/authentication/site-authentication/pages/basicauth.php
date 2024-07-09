<?php
// Function to validate the username and password
function validateCredentials($username, $password) {
    // Set the valid username and password
    $valid_username = 'admin';
    $valid_password = 'admin';

    // Check if the provided credentials match the valid credentials
    return $username === $valid_username && $password === $valid_password;
}

// Check if the user has provided credentials via HTTP Basic Auth
if (!isset($_SERVER['PHP_AUTH_USER']) || !validateCredentials($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
    // If not, or if the credentials are invalid, send headers to prompt for Basic Auth
    header('WWW-Authenticate: Basic realm="Restricted Area"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Unauthorized access'; // Display this message if authentication fails
    exit;
} else {
    // If valid credentials are provided
    echo "<p>Welcome, {$_SERVER['PHP_AUTH_USER']}!</p>";
    // Here you can continue with the rest of your secure content or logic
}
