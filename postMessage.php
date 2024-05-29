<?php

$servername = "localhost";
$username = "root";
$dbName = "messageboard";

$conn = new mysqli($servername, $username, "", $dbName);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$message = "";

if (!is_null($_POST["m"]) && $_POST != "") {

    $conn->query("INSERT INTO `messages` (`id`, `message`, `posted`, `hidden`) VALUES (NULL, '{$_POST['m']}', current_timestamp(), '0');");
} else {

}

header('Location: ' . $_SERVER['HTTP_REFERER']);