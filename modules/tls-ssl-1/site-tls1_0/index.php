<!DOCTYPE html>
<html>

<head>
        <title>TLS 1.0</title>
</head>

<body>
<h1>Welcome to TLS 1.0</h1>
<h2>Casual reflected xss</h2>

<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
  Name: <input type="text" name="name">
  <input type="submit">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // collect value of input field
    $name = $_POST['name'];
    echo "Hello, " . $name;
}
?>

</body>
</html>
