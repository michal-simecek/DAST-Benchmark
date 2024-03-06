
<!DOCTYPE html>
<html>

<head>
        <title>Denial of Service</title>
</head>
<body>
<h2>Denial of service - encoding</h2>
<form method="post">
    <div class="container">
      <label for="uname"><b>Username</b></label>
      <input type="text" placeholder="Enter Username" name="uname" required>
  
      <label for="psw"><b>Password</b></label>
      <input type="password" placeholder="Enter Password" name="psw" required>
      <input type="hidden" name="action" value="dos-encoded">
      <button type="submit">Login</button>
    </div>
  </form> 
<br><br><br>
  <h2>Denial of service - 100ms delay per character, 2000ms delay for quotes</h2>
<form method="post">
    <div class="container">
      <label for="name"><b>check if username is available</b></label>
      <input type="text" placeholder="Enter Username" name="name" required>
      <input type="hidden" name="action" value="dos-delay">
  
      <button type="submit">check</button>
    </div>
  </form> 

  <h2>Denial of service - parameter name reflected</h2>
<form method="post">
    <div class="container">
      <label for="name"><b>check if username is available</b></label>
      <input type="text" placeholder="Enter Username" name="name" required>
      <input type="hidden" name="action" value="parameter_name">
  
      <button type="submit">check</button>
    </div>
  </form> 

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $action = $_POST['action'];

  switch ($action) {
      case 'dos-delay':
          dos_delay();
          break;
      case 'dos-encoded':
          dos_encoded();
          break;
      case 'parameter_name':
          parameter_name_dos();
          break;
      default:
          echo "something went wrong";
          break;
  }
}

    # instead of semicolom (') the server returns &#039; which is 6 times the original size
    function dos_encoded() {
      $name = $_POST['uname'];
      echo "<p> user " . htmlspecialchars($name). " not found </p>";
    }

    # foreach character 100ms delay to respond
    # incase of quote add 2000ms
    function dos_delay(){
      $string = $_POST['name'];
      foreach (str_split($string) as $char){
        if($char === "'"){
          usleep(2000000);
        }else{
          usleep(100000);
        }
      }
      echo "schrodinger's username";
    }

    function parameter_name_dos(){
      echo htmlspecialchars(key($_POST));
    }
  ?>

</body>
</html>

