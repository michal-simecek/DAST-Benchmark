<?php
function base64UrlDecode($data) {
    return base64_decode(strtr($data, '-_', '+/'));
}

function decodeJWT($jwt, $secret) {
    if (empty($jwt)) {
        return null; // Return null if no token is provided
    }

    // Split the JWT into its three parts
    $tokenParts = explode('.', $jwt);
    if (count($tokenParts) !== 3) {
        return null; // Incorrect segments, invalid JWT
    }

    $header = base64UrlDecode($tokenParts[0]);
    $payload = base64UrlDecode($tokenParts[1]);
    $signatureProvided = $tokenParts[2];

    // Generate a signature based on the header and payload using the secret
    $base64UrlHeader = base64UrlEncode($header);
    $base64UrlPayload = base64UrlEncode($payload);
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
    $base64UrlSignature = base64UrlEncode($signature);

    // Compare the provided signature with the generated one
    if (!hash_equals(base64UrlDecode($base64UrlSignature), base64UrlDecode($signatureProvided))) {
        return null; // Signature verification failed
    }

    return json_decode($payload, true);
}

function base64UrlEncode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

// Extract JWT from Authorization header
$headers = getallheaders();
$authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : null;

if (!$authHeader) {
    http_response_code(401);
    exit('Unauthorized: No JWT provided.');
}

$jwt = trim(str_replace("Bearer", "", $authHeader));
$secretKey = "secret";

$decodedData = decodeJWT($jwt, $secretKey);

if ($decodedData) {
    echo "JWT is valid. Payload:\n";
    print_r($decodedData);
} else {
    http_response_code(401);
    exit('Unauthorized: JWT is invalid or the signature does not match.');
}

// Your secure PHP code goes here after the JWT is validated
echo "This is a secure area after JWT validation.";
