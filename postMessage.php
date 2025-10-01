<?php
session_start();

$servername = $_ENV['DB_HOST'] ?? "localhost";
$username = $_ENV['DB_USER'] ?? "root";
$password = $_ENV['DB_PASSWORD'] ?? ""; // change these credentials as you wish
$dbName = $_ENV['DB_NAME'] ?? "messageboard";

$conn = new mysqli($servername, $username, $password, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql_output = "";

try {
    if (isset($_POST["m"]) && $_POST["m"] != "") {
        $message = $_POST['m']; // the sql injection here is crazy
        $query = "INSERT INTO `messages` (`message`) VALUES ('$message');";
        echo $query;

        if ($conn->query($query) === TRUE) {
            $last_id = $conn->insert_id;
            $result_query = "SELECT * FROM messages WHERE id = $last_id";
            $result = $conn->query($result_query);
            if ($result->num_rows > 0) {
                $inserted_message = $result->fetch_assoc();
                $sql_output = "ID: " . $inserted_message['id'] . ", Message: " . $inserted_message['message'] . ", Posted: " . $inserted_message['posted'] . ", Hidden: " . $inserted_message['hidden'];
            } else {
                $sql_output = "Error retrieving message.";
            }
        } else {
            $sql_output = "Error inserting message: " . $conn->error;
        }
    } else {
        $sql_output = "No message was provided.";
    }    
} catch (Exception $e) {
    $sql_output = $e->getMessage();
}


$_SESSION['sql_output'] = $sql_output;
header('Location: ' . $_SERVER['HTTP_REFERER']);

$conn->close();
?>
