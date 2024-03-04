
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

<h2>sanitized reflected xss</h2>

<form method="post">
  Name: <input type="text" name="name">
  <input type="hidden" name="action" value="sanitized_xss">
  <input type="submit">
</form>

<h2>reflected xss in email field</h2>

<form method="post">
  Email: <input type="text" name="email">
  <input type="hidden" name="action" value="email_xss">
  <input type="submit">
</form>

<h2>reflected xss with blacklisted words</h2>

<form method="post">
  Name: <input type="text" name="name">
  <input type="hidden" name="action" value="blacklisted_xss">
  <input type="submit">
</form>


<h2 hidden>Hidden reflected xss</h2>

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
        case 'sanitized_xss':
          sanitized_xss();
          break;
        case 'email_xss':
          email_xss();
          break;
        case 'blacklisted_xss':
          blacklisted_xss();
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

function email_xss(){
  // example payload: "><svg/onload=alert(1)>"@x.y
  $email = $_POST['email'];
  if(filter_var($email, FILTER_VALIDATE_EMAIL)){
    echo "your email is " . $email;
  }
  else{
    echo "invalid email";
  }
}

function casual_xss(){
$name = $_POST['name'];
echo "Hello, " . $name;
}

function sanitized_xss(){
  // reflected in script tags for now, feel free to make it more creative :)
$name = $_POST['name'];
echo "<script>". htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . "</script>";
}

function blacklisted_xss(){
  $name = $_POST['name'];
  if(!blacklisted($name)){
    echo "Hello, " . $name;
  }else{
    echo "forbidden words in string";
  }
}

function blacklisted($string){
  //easily bypassable by using capital cases
  $blacklist = 'javascript|<script>|<svg>|onload|onerror|img|alert|ALERT';
  if (preg_match ("/$blacklist/", $string))
  {return true;}
  else{return false;}
}
?>

</body>
</html>

