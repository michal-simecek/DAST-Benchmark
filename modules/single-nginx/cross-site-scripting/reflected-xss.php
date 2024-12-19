<!DOCTYPE html>
<html>

<head>
        <title>Cross Site Scripting</title>
</head>

<body>
<h2>casual reflected xss</h2>

<form method="post">
  Name: <input type="text" name="casual_xss">
  <input type="hidden" name="action" value="casual_xss">
  <input type="submit">
</form>

<h2>lenght limited reflected xss</h2>

<form method="post">
  Name (max 20 characters): <input type="text" name="length_xss" maxlenght="15">
  <input type="hidden" name="action" value="length_xss">
  <input type="submit">
</form>

<h2>sanitized xss reflected in script tags</h2>

<form method="post">
  Name: <input type="text" name="sanitized_xss">
  <input type="hidden" name="action" value="sanitized_xss">
  <input type="submit">
</form>

<h2>reflected xss in email field</h2>

<form method="post">
  Email: <input type="text" name="email_xss">
  <input type="hidden" name="action" value="email_xss">
  <input type="submit">
</form>

<h2>reflected xss with blacklisted words</h2>

<form method="post">
  Name: <input type="text" name="blacklisted_xss">
  <input type="hidden" name="action" value="blacklisted_xss">
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
  $name = $_POST['length_xss'];
  if(strlen($name) <= 20){
    echo "Hello, " . $name;
  }
  else{
    echo "name is too long";
  }
}

function email_xss(){
  // example payload: "<script>alert(1)</script>"@example.com
  // , FILTER_VALIDATE_EMAIL
  $email = $_POST['email_xss'];
  if(isEmail($email)){
    echo "your email is " . $email;
  }
  else{
    echo "invalid email";
  }
}

function isEmail($email) {
  // Regular expression that checks for the basic structure of an email
  $pattern = '/^[^@]+@[^@]+\.[^@]+$/';
  
  // Returns true if the email matches the pattern, false otherwise
  return preg_match($pattern, $email);
}

function casual_xss(){
$name = $_POST['casual_xss'];
echo "Hello, " . $name;
}

function sanitized_xss(){
$name = $_POST['sanitized_xss'];
echo "<script>". htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . "</script>";
}

function blacklisted_xss(){
  $name = $_POST['blacklisted_xss'];
  if(!blacklisted($name)){
    echo "Hello, " . $name;
  }else{
    echo "forbidden words in string";
  }
}

function blacklisted($string){
  $blacklist = 'javascript|script|svg|onload|onerror|img|alert|ALERT';
  if (preg_match ("/$blacklist/", $string))
  {return true;}
  else{return false;}
}
?>

</body>
</html>

