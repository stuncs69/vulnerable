<?php
session_start();

$servername = "localhost";
$username = "root";
$password = ""; // Assuming no password is set
$dbName = "messageboard";

$conn = new mysqli($servername, $username, $password, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql_output = "";

if (isset($_POST["m"]) && $_POST["m"] != "") {
    $message = $_POST['m'];
    $query = "INSERT INTO `messages` (`id`, `message`, `posted`, `hidden`) VALUES (NULL, '$message', current_timestamp(), '0')";

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

$_SESSION['sql_output'] = $sql_output;
header('Location: ' . $_SERVER['HTTP_REFERER']);

$conn->close();
