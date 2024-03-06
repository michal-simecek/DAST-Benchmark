
<!DOCTYPE html>
<html>

<head>
        <title>TLS 1.3</title>
</head>

<body>
<h1>Welcome to TLS 1.3</h1>
<h2>Casual reflected xss</h2>

<form method="post">
  Name: <input type="text" name="name">
  <input type="hidden" name="action" value="casual_xss">
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

