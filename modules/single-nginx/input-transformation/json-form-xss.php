<?php
function parseJsonInput($jsonString) {
    $data = json_decode($jsonString, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return null;
    }
    return $data;
}

if (isset($_POST['data'])) {
    $data = parseJsonInput($_POST['data']);
    if ($data) {
        $name = $data['name'];
        $message = $data['message'];
    } else {
        $name = $message = 'Invalid JSON';
    }
} else {
    $name = $message = '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>JSON Form XSS Vulnerability</title>
    <script>
        function createJsonString() {
            var name = document.getElementById('name').value;
            var message = document.getElementById('message').value;

            var jsonData = {
                "name": name,
                "message": message
            };

            var jsonString = JSON.stringify(jsonData);

            document.getElementById('data').value = jsonString;
            return true;
        }
    </script>
</head>
<body>
    <h1>Submit Your Message</h1>
    <form method="POST" action="" onsubmit="return createJsonString();">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name"><br><br>

        <label for="message">Message:</label><br>
        <input type="text" id="message" name="message"><br><br>

        <input type="hidden" id="data" name="data">
        <button type="submit">Submit</button>
    </form>

    <?php if ($name && $message): ?>
        <h2>Submitted Data:</h2>
        <p>Name: <?php echo htmlspecialchars($name); ?></p>
        <p>Message: <?php echo $message; ?></p>
    <?php endif; ?>
</body>
</html>
