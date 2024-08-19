<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Links Generator</title>
</head>
<body>
    <h1>Links Generator</h1>
    
    <form method="POST" action="">
        <label for="language">Enter a language: </label>
        <input type="text" id="language" name="language" required>
        <input type="submit" value="Generate Links">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $language = htmlspecialchars($_POST["language"], ENT_QUOTES, 'UTF-8');

        if (!empty($language)) {
            echo "<h2>Links related to " . $language . ":</h2>";
            echo "<ul>";
            
            for ($i = 1; $i <= 20; $i++) {
                echo "<li><a href='#'>Learn more about " . $language . " - Resource " . $i . "</a></li>";
            }

            echo "</ul>";
        } else {
            echo "<p>Please enter a language.</p>";
        }
    }
    ?>
</body>
</html>
