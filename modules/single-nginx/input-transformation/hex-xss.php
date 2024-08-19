<?php
function hex2str($hex) {
    $str = '';
    for ($i = 0; $i < strlen($hex); $i += 2) {
        $str .= chr(hexdec(substr($hex, $i, 2)));
    }
    return $str;
}

if (isset($_GET['input'])) {
    $input = hex2str($_GET['input']);
} else {
    $input = '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hex Encoding</title>
    <script>
        function encodeInput() {
            var inputField = document.getElementById('input');
            var inputValue = inputField.value;
            var hexEncodedValue = '';

            for (var i = 0; i < inputValue.length; i++) {
                hexEncodedValue += inputValue.charCodeAt(i).toString(16);
            }

            inputField.value = hexEncodedValue;
            return true;
        }
    </script>
</head>
<body>
    <h1>Hex Encoding</h1>
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
