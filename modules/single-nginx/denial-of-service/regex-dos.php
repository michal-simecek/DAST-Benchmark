<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Validation</title>
</head>
<body>
    <h1>Email Validation</h1>
    
    <form method="POST" action="">
        <label for="username">Enter email: </label>
        <input type="text" id="username" name="username" required>
        <input type="hidden" name="email" value="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$">
        <input type="submit" value="Submit">
    </form>

    <?php
    ini_set('pcre.backtrack_limit', -1);
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $pattern = '/' . $_POST["email"] . '/';

        if (preg_match($pattern, $username)) {
            echo "<p>It is a valid email address.</p>";
        } else {
            echo "<p>It is not a valid email address.</p>";
        }
    }
    ?>
</body>
</html>
