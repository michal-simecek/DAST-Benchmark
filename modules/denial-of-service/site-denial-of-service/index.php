
<!DOCTYPE html>
<html>

<head>
        <title>TLS 1.3</title>
</head>

<form method="post">
    <div class="container">
      <label for="uname"><b>Username</b></label>
      <input type="text" placeholder="Enter Username" name="uname" required>
  
      <label for="psw"><b>Password</b></label>
      <input type="password" placeholder="Enter Password" name="psw" required>
  
      <button type="submit">Login</button>
    </div>
  </form> 

  <?php
  # instead of semicolom (') the server returns &#039; which is 6 times the original size
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $name = $_POST['uname'];
      echo "<p> user " . htmlspecialchars($name). " not found </p>";
    }
  ?>

</body>
</html>

