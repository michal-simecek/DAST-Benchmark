<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['uploaded_file'])) {
    $targetDirectory = "uploads/";
    $targetFile = $targetDirectory . basename($_FILES['uploaded_file']['name']);
    $uploadOk = 1;

    // Check if file already exists
    if (file_exists($targetFile)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // if everything is ok, try to upload file
        if (move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $targetFile)) {
            echo "The file " . htmlspecialchars(basename($_FILES['uploaded_file']['name'])) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>File Upload and Download</title>
</head>
<body>

<h2>Upload File</h2>
<form action="index.php" method="post" enctype="multipart/form-data">
    Select file to upload:
    <input type="file" name="uploaded_file" id="uploaded_file">
    <input type="submit" value="Upload File" name="submit">
</form>

<h2>Download Files</h2>
<?php
$files = scandir('uploads/');
foreach ($files as $file) {
    if ($file != "." && $file != "..") {
        echo '<p><a href="uploads/' . urlencode($file) . '" download>' . htmlspecialchars($file) . '</a></p>';
    }
}
?>

</body>
</html>