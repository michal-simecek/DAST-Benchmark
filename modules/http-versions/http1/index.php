
<!DOCTYPE html>
<html>

<head>
        <title>HTTP 1</title>
</head>

<body>
<h1>Welcome to HTTP 1</h1>
<h2>Casual reflected xss</h2>

<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
  Name: <input type="text" name="name">
  <input type="submit">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    echo "Hello, " . $name;
}
?>

</body>
</html>


