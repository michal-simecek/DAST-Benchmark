
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

<h2>lenght limited reflected xss</h2>

<form method="post">
  Name (max 20 characters): <input type="text" name="name" maxlenght="15">
  <input type="hidden" name="action" value="length_xss">
  <input type="submit">
</form>


<h2>Hidden reflected xss</h2>

<form hidden method="post">
  Name: <input type="text" name="name">
  <input type="hidden" name="action" value="hidden_xss">
  <input type="submit">
</form>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    switch ($action) {
        case 'length_xss':
            length_xss();
            break;
        case 'casual_xss':
            casual_xss();
            break;
        case 'hidden_xss':
            casual_xss();
            break;
        default:
            echo "something went wrong";
            break;
    }
}

function length_xss(){
  // example payload: <svg/onload=alert()>
  $name = $_POST['name'];
  if(strlen($name) <= 20){
    echo "Hello, " . $name;
  }
  else{
    echo "name is too long";
  }
}

function casual_xss(){
$name = $_POST['name'];
echo "Hello, " . $name;
}
?>

</body>
</html>

