<?php
session_start();

$secret_key = "dastbenchmarksecretkey";
$valid_username = 'admin';
$valid_password = 'dastbenchmark';

function createJWT($header, $payload, $secret) {
    $header_encoded = base64UrlEncode(json_encode($header));
    $payload_encoded = base64UrlEncode(json_encode($payload));
    $signature = hash_hmac('SHA256', "$header_encoded.$payload_encoded", $secret, true);
    $signature_encoded = base64UrlEncode($signature);

    return "$header_encoded.$payload_encoded.$signature_encoded";
}

function decodeJWT($jwt, $secret) {
    $token_parts = explode('.', $jwt);
    if(count($token_parts) !== 3) {
        return false;
    }

    list($header_encoded, $payload_encoded, $signature_encoded) = $token_parts;

    $header = json_decode(base64UrlDecode($header_encoded), true);
    $payload = json_decode(base64UrlDecode($payload_encoded), true);
    $signature = base64UrlDecode($signature_encoded);

    $valid_signature = hash_hmac('SHA256', "$header_encoded.$payload_encoded", $secret, true);

    if($signature === $valid_signature) {
        return $payload;
    } else {
        return false;
    }
}

function base64UrlEncode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64UrlDecode($data) {
    return base64_decode(strtr($data, '-_', '+/'));
}

// login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $valid_username && $password === $valid_password) {
        $header = ['alg' => 'HS256', 'typ' => 'JWT'];
        $payload = ['username' => $username, 'iat' => time(), 'exp' => time() + 3600];

        $jwt = createJWT($header, $payload, $secret_key);
        setcookie("jwt", $jwt, time() + 3600, "/"); // Set JWT as a cookie
        header("Location: jwt.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}

// logout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    setcookie("jwt", "", time() - 3600, "/"); // Expire the JWT cookie
    header("Location: jwt.php");
    exit();
}

// Check for JWT
$loggedin = false;
$input = "";
if (isset($_COOKIE['jwt'])) {
    $jwt = $_COOKIE['jwt'];
    $payload = decodeJWT($jwt, $secret_key);
    if ($payload && isset($payload['username'])) {
        $loggedin = true;
    }
}

// XSS
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $loggedin && isset($_GET['input'])) {
    $input = $_GET['input'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login and Protected Page</title>
</head>
<body>
    <?php if (!$loggedin): ?>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <button type="submit" name="login">Login</button>
        </form>
        <?php if (isset($error)) echo "<p>$error</p>"; ?>
    <?php else: ?>
        <form method="POST" action="">
            <button type="submit" name="logout">Logout</button>
        </form>
        <form method="GET" action="">
            <label for="input">Enter something:</label>
            <input type="text" id="input" name="input" required>
            <button type="submit">Submit</button>
        </form>
        <?php if ($input) echo "You submitted: " . $input; ?>
    <?php endif; ?>
</body>
</html>
