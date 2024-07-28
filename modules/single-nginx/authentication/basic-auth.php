<?php
function validateCredentials($username, $password) {
    $valid_username = 'admin';
    $valid_password = 'dastbenchmark';

    return $username === $valid_username && $password === $valid_password;
}

// Check if the user has provided credentials via HTTP Basic Auth
if (!isset($_SERVER['PHP_AUTH_USER']) || !validateCredentials($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
    header('WWW-Authenticate: Basic realm="Restricted Area"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Unauthorized access'; // Display this message if authentication fails
    exit;
} else {
    echo "<p>Welcome, {$_SERVER['PHP_AUTH_USER']}!</p>";
}
