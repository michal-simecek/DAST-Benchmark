<?php
session_start();

function generateCsrfToken() {
    return bin2hex(random_bytes(32));
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = generateCsrfToken();
}

$csrf_token = $_SESSION['csrf_token'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitted_token = $_POST['csrf_token'] ?? '';

    if ($submitted_token === $_SESSION['csrf_token']) {
        echo "Form submission successful!";
    } else {
        echo "Invalid CSRF token!";
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['data'])) {
    // Intentional Vulnerability: Allow form submission via GET request without CSRF validation
    echo "Form submission successful!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CSRF</title>
</head>
<body>
    <form method="POST" action="">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
        <label for="data">Enter some data:</label>
        <input type="text" id="data" name="data" required>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
