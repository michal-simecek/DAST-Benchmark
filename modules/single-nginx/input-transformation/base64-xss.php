<?php
if (isset($_GET['input'])) {
    $input = base64_decode($_GET['input']);
} else {
    $input = '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Base64 encoding</title>
    <script>
        function encodeInput() {
            var inputField = document.getElementById('input');
            var encodedValue = btoa(inputField.value);
            inputField.value = encodedValue;
            return true;
        }
    </script>
</head>
<body>
    <h1>Base64 encoding</h1>
    <form method="GET" action="" onsubmit="return encodeInput();">
        <label for="input">Enter something:</label>
        <input type="text" id="input" name="input">
        <button type="submit">Submit</button>
    </form>

    <?php if ($input): ?>
        <p>You entered: <?php echo $input; ?></p>
    <?php endif; ?>
</body>
</html>
