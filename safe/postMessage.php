<?php
session_start();

$servername = "localhost";
$username = "root";
$password = ""; // change these credentials as you wish
$dbName = "messageboard";

$conn = new mysqli($servername, $username, $password, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql_output = "";

try {
    if (isset($_POST["m"]) && $_POST["m"] != "") {
        $message = $_POST['m'];
        $stmt = $conn->prepare("INSERT INTO messages (message) VALUES (?)");
        $stmt->bind_param("s", $message);
        $stmt->execute();
        $stmt->close();
    }
} catch (Exception $e) {
    $sql_output = $e->getMessage();
}

$_SESSION['sql_output'] = $sql_output;
header('Location: ' . $_SERVER['HTTP_REFERER']);

$conn->close();
?>
