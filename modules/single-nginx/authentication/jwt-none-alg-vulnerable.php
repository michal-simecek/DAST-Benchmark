<?php
session_start();

$secret_key = "dastbenchmarksecretkey";
$admin_username = 'admin';

function createJWT($header, $payload, $secret) {
    $header_encoded = base64UrlEncode(json_encode($header));
    $payload_encoded = base64UrlEncode(json_encode($payload));
    
    if ($header['alg'] === 'none') {
        $signature_encoded = '';
    } else {
        $signature = hash_hmac('SHA256', "$header_encoded.$payload_encoded", $secret, true);
        $signature_encoded = base64UrlEncode($signature);
    }

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

    if ($header['alg'] === 'none') {
        return $payload;
    } else {
        $valid_signature = hash_hmac('SHA256', "$header_encoded.$payload_encoded", $secret, true);
        if($signature === $valid_signature) {
            return $payload;
        } else {
            return false;
        }
    }
}

function base64UrlEncode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64UrlDecode($data) {
    return base64_decode(strtr($data, '-_', '+/'));
}


// Check for JWT
$loggedin = false;
$is_admin = false;

if (isset($_COOKIE['jwt'])) {
    $jwt = $_COOKIE['jwt'];
    $payload = decodeJWT($jwt, $secret_key);
    if ($payload && isset($payload['username'])) {
        $loggedin = true;
        if ($payload['username'] === $admin_username) {
            $is_admin = true;
        }
    }
} else {
    // Generate JWT token for all visitors
    $header = ['alg' => 'HS256', 'typ' => 'JWT'];
    $payload = ['username' => 'guest', 'iat' => time(), 'exp' => time() + 3600];
    $jwt = createJWT($header, $payload, $secret_key);
    setcookie("jwt", $jwt, time() + 3600, parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
}

$message = '';
if ($loggedin) {
    $message = $is_admin ? "You are an admin!" : "You are a guest!";
} else {
    $message = "You are not logged in.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Protected Page</title>
</head>
<body>
    <form method="POST" id="authForm">
        <button type="submit" name="authenticate">Authenticate</button>
    </form>
    <p id="message">
        <?php if(isset($_POST['authenticate'])) {echo htmlspecialchars($message);} ?>
    </p>
</body>
</html>