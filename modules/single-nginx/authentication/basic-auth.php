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
    echo 'Unauthorized access';
    exit;
} else {
    if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['message']) {
        echo '<p>' . $_POST['message'] . '</p>';
    } else {
        echo '<form action="" method="post">';
        echo "    <label for='message'>Enter your message:</label><br>";
        echo "    <input type='text' id='message' name='message'><br>";
        echo '    <input type="submit" value="Submit">';
        echo '</form>';    
    }

}
?>
