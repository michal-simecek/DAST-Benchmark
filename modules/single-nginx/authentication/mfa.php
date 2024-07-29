<?php
session_start();

function generateTotpCode($secret, $timeSlice = null) {
    if ($timeSlice === null) {
        $timeSlice = floor(time() / 30);
    }
    $secretKey = base32Decode($secret);
    $time = chr(0).chr(0).chr(0).chr(0).pack('N*', $timeSlice);
    $hm = hash_hmac('SHA1', $time, $secretKey, true);
    $offset = ord(substr($hm, -1)) & 0x0F;
    $hashPart = substr($hm, $offset, 4);
    $value = unpack('N', $hashPart);
    $value = $value[1] & 0x7FFFFFFF;
    $modulo = 10 ** 6;
    return str_pad($value % $modulo, 6, '0', STR_PAD_LEFT);
}

function base32Decode($secret) {
    if (empty($secret)) return '';
    $base32Chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    $base32CharsFlipped = array_flip(str_split($base32Chars));
    $paddingCharCount = substr_count($secret, '=');
    $allowedValues = [6, 4, 3, 1, 0];
    if (!in_array($paddingCharCount, $allowedValues)) return false;
    for ($i = 0; $i < 4; $i++) {
        if ($paddingCharCount === $allowedValues[$i] &&
            substr($secret, -($allowedValues[$i])) !== str_repeat('=', $allowedValues[$i])) return false;
    }
    $secret = str_replace('=', '', $secret);
    $binaryString = '';
    for ($i = 0; $i < strlen($secret); $i = $i + 8) {
        $x = '';
        if (!in_array($secret[$i], str_split($base32Chars))) return false;
        for ($j = 0; $j < 8; $j++) {
            $x .= str_pad(base_convert($base32CharsFlipped[$secret[$i + $j]], 10, 2), 5, '0', STR_PAD_LEFT);
        }
        $eightBits = str_split($x, 8);
        foreach ($eightBits as $eightBit) {
            $binaryString .= chr(base_convert($eightBit, 2, 10));
        }
    }
    return $binaryString;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === 'admin' && $password === 'dastbenchmark') {
        $_SESSION['username'] = $username;

        $secret = 'JBSWY3DPEHPK3PXP';

        $_SESSION['tfa_secret'] = $secret;

        echo '<form action="" method="post">';
        echo '2FA Code: <input type="text" name="2fa_code" required><br>';
        echo '<button type="submit">Verify</button>';
        echo '</form>';
        exit;
    } else {
        echo 'Invalid credentials.';
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['2fa_code'])) {
    $secret = $_SESSION['tfa_secret'];
    $code = $_POST['2fa_code'];

    if (generateTotpCode($secret) === $code) {
        $_SESSION['authenticated'] = true;
        header("Location: mfa.php");
    } else {
        echo 'Invalid 2FA code.';
    }
    exit;
}

if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['message'])) {
        $message = $_GET['message'];
        echo "<p>You entered: $message</p>";
    }
    echo '<form action="" method="get">';
    echo 'Enter a message: <input type="text" name="message" required><br>';
    echo '<button type="submit">Submit</button>';
    echo '</form>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <form action="" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
