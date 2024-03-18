<?php
// Display errors to simulate a vulnerable environment
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = 'db'; // Adjust the hostname as necessary
$username = 'php_docker'; // Adjust the username as necessary
$password = 'password'; // Adjust the password as necessary
$database = 'php_docker'; // Adjust the database name as necessary

// Create connection
echo "test";
$conn = new mysqli($host, $username, $password, $database);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    switch ($action) {
        case 'error_sql':
            error_sql($conn);
            break;
        case 'time_sql':
            time_sql($conn);
            break;
        default:
            echo "something went wrong";
            break;
    }
}

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

# vulnerable payload:
# admin' union select 'password' from users where username = 'admin

function error_sql($conn){
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

function time_sql($conn){

    //payload: `admin' AND SLEEP(5)=0 -- ` (dont forget the space at the end)
    
    $userInput = $_POST['username'];
    $passwordInput = $_POST['password']; // Assume there's a password field in the form.

    // Time starts here
    $startTime = microtime(true);

    // The SQL query is vulnerable to time-based SQL injection
    $sql = "SELECT * FROM users WHERE username = '$userInput'";

    if ($result = $conn->query($sql)) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // In a real scenario, you would hash and check the password here
            // For demonstration, we simulate successful login if the user exists
            echo "Login Successful";
        }
        // Time ends after query execution
        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;
        echo "Execution time: " . round($executionTime, 4) . " seconds.";
    } else {
        $message = "Error in query execution.";
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
        <input type="hidden" name="action" value="error_sql">
        <input type="submit" value="Submit">
    </form>

    <h2>Login Form (Time-based SQL Injection Vulnerable)</h2>
    <form action="" method="post">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" value=""><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" value=""><br><br>
        <input type="hidden" name="action" value="time_sql">
        <input type="submit" value="Login">
    </form>

    <p><?php echo $message; ?></p>
</body>
</html>