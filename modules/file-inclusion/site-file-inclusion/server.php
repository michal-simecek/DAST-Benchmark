file server:

<?php
// display.php

if (isset($_GET['file'])) {
    $fileName = urldecode($_GET['file']); // Decode the file name
    $filePath = "uploads/" . basename($fileName); // Construct the file path

    if (file_exists($filePath)) {
        // Ensure the file does not contain executable PHP code (for security)
        $fileContent = htmlspecialchars(file_get_contents($filePath));
        echo "<pre>{$fileContent}</pre>"; // Display the file content in a preformatted text block
    } else {
        echo "File does not exist.";
    }
} else {
    echo "No file specified.";
}
?>