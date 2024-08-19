<?php
function parseXmlInput($xmlString) {
    libxml_use_internal_errors(true);
    $xml = simplexml_load_string($xmlString, "SimpleXMLElement", LIBXML_NOCDATA);
    if ($xml === false) {
        return null;
    }
    return $xml;
}

if (isset($_POST['data'])) {
    $xml = parseXmlInput($_POST['data']);
    if ($xml) {
        $name = base64_decode((string)$xml->name);
        $message = base64_decode((string)$xml->message);
    } else {
        $name = $message = 'Invalid XML';
    }
} else {
    $name = $message = '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>XML Form XSS Vulnerability with Base64</title>
    <script>
        function createXmlString() {
            var name = document.getElementById('name').value;
            var message = document.getElementById('message').value;

            var encodedName = btoa(name);
            var encodedMessage = btoa(message);

            var xmlString = '<formData>';
            xmlString += '<name>' + encodedName + '</name>';
            xmlString += '<message>' + encodedMessage + '</message>';
            xmlString += '</formData>';

            document.getElementById('data').value = xmlString;
            return true;
        }
    </script>
</head>
<body>
    <h1>Submit Your Message</h1>
    <form method="POST" action="" onsubmit="return createXmlString();">
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
