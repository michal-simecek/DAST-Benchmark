<?php
// Display errors to simulate a vulnerable environment
ini_set('display_errors', 1);
error_reporting(E_ALL);

// MySQL database credentials
$mysql_host = 'db';
$mysql_username = 'admin';
$mysql_password = 'password';
$mysql_database = 'DASTDB';

// MongoDB database credentials
$mongo_host = 'mongodb://admin:adminpass@mongodb:27017';
$mongo_collection = 'DASTDB';

// MongoDB connection
$manager = new MongoDB\Driver\Manager($mongo_host);

// MySQL connection
$mysqli = new mysqli($mysql_host, $mysql_username, $mysql_password, $mysql_database);

if ($mysqli->connect_error) {
    die("MySQL Connection failed: " . $mysqli->connect_error);
}

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
        case 'vulnerable_nosql':
            vulnerable_nosql($manager, $mongo_collection);
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

function vulnerable_nosql($manager, $databaseName){
    $userId = $_POST['userId'];

    $filter = ['username' => $userId]; // Vulnerable to NoSQL injection
    $options = [];
    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = $manager->executeQuery($databaseName . '.users', $query);

    echo "<h3>Results:</h3>";
    if ($cursor) {
        foreach ($cursor as $document) {
            $document = json_decode(json_encode($document), true);
            foreach ($document as $key => $value) {
                if (is_array($value)) {
                    echo htmlspecialchars($key) . ": " . json_encode($value) . "<br>";
                } else {
                    echo htmlspecialchars($key) . ": " . htmlspecialchars($value) . "<br>";
                }
            }
        }
    } else {
        echo "0 results";
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

    <h2>NoSQL Injection Vulnerable Form</h2>
    <form action="" method="post">
        <label for="userId">User ID:</label><br>
        <input type="text" id="userId" name="userId" value=""><br><br>
        <input type="hidden" name="action" value="vulnerable_nosql">
        <input type="submit" value="Submit">
    </form>
</body>
</html>
