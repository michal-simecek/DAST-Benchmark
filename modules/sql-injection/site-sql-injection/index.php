<?php
// Display errors to simulate a vulnerable environment
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = 'db'; // Adjust the hostname as necessary
$username = 'php_docker'; // Adjust the username as necessary
$password = 'password'; // Adjust the password as necessary
$database = 'php_docker'; // Adjust the database name as necessary

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

# vulnerable payload:
# admin' union select 'password' from users where username = 'admin

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST['userId'];

    // Vulnerable SQL query
    $sql = "SELECT username FROM users WHERE username = '$userId'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            foreach ($row as $column => $value) {
                $message .= htmlspecialchars($column) . ": " . htmlspecialchars($value) . "<br>";
    }}}else {
        $message = "0 results";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vulnerable Form</title>
</head>
<body>
    <h2>Vulnerable SQL Injection Form</h2>
    <form action="" method="post">
        <label for="userId">User ID:</label><br>
        <input type="text" id="userId" name="userId" value=""><br><br>
        <input type="submit" value="Submit">
    </form>

    <p><?php echo $message; ?></p>
</body>
</html>