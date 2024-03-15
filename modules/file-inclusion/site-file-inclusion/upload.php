<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    exec("chown -R www-data:www-data files/");
    $target_dir = "files/";

    $directory = '~/files/';
    echo exec("sudo mkdir ~/files/ -v");
    echo exec("ls -l ./");
    echo exec('sudo mv /tmp/phpcoKSND ~/files/test.pdf');
    $user = 'www-data'; // Change this to your web server user
    $group = 'www-data'; // Change this to your web server group
    
    // Change ownership to www-data:www-data
    if (chown($directory, $user) && chgrp($directory, $group)) {
        echo "Ownership changed successfully!";
    } else {
        echo "Failed to change ownership!";
    }
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "txt" && $imageFileType != "pdf" && $imageFileType != "docx" && $imageFileType != "xlsx") {
        echo "Sorry, only TXT, PDF, DOCX, XLSX files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
} else {
    // Display file upload form
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>File Upload</title>
    </head>
    <body>
        <h1>File Upload</h1>
        <form action="upload.php" method="post" enctype="multipart/form-data">
            Select file to upload:
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Upload File" name="submit">
        </form>
    </body>
    </html>
    <?php
}
?>
