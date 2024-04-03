<?php
// Display errors to simulate a vulnerable environment
ini_set('display_errors', 1);
error_reporting(E_ALL);

// MySQL database credentials
$mysql_host = 'db'; // Adjust the hostname as necessary
$mysql_username = 'php_docker'; // Adjust the username as necessary
$mysql_password = 'password'; // Adjust the password as necessary
$mysql_database = 'php_docker'; // Adjust the database name as necessary

// MongoDB database credentials
$mongo_host = 'localhost';
$mongo_username = 'mongoadmin';
$mongo_password = 'mongoadminpassword';
$mongo_database = 'DASTDB'; // Adjust the database name as necessary

// MySQL connection
$mysqli = new mysqli($mysql_host, $mysql_username, $mysql_password, $mysql_database);

if ($mysqli->connect_error) {
    die("MySQL Connection failed: " . $mysqli->connect_error);
}

// MongoDB connection
//require_once __DIR__ . '/vendor/autoload.php';

//$mongo_client = new MongoDB\Client("mongodb://$mongo_username:$mongo_password@$mongo_host");

//$mongo_db = $mongo_client->selectDatabase($mongo_database);
//$mongo_collection = $mongo_db->users;

//$users = $mongo_collection->find([]);

// // Echo details of each user
// foreach ($users as $user) {
//     echo "Username: " . $user['username'] . "<br>";
//     echo "Password: " . $user['password'] . "<br>";
//     echo "ID: " . $user['_id'] . "<br><br>";
// }


// Create connection
$conn = new mysqli($mysql_host, $mysql_username, $mysql_password, $mysql_database);

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
                echo htmlspecialchars($column) . ": " . htmlspecialchars($value) . "<br>";
    }}}else {
        echo "0 results";
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
        echo "Error in query execution.";
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
</body>
</html>