<?php
session_start();

$servername = "localhost";
$username = "root";
$dbName = "messageboard";

$conn = new mysqli($servername, $username, "", $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Board</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .scrollable {
            max-height: 400px;
            overflow-y: auto;
        }


        .scrollable::-webkit-scrollbar {
            width: 12px;
        }

        .scrollable::-webkit-scrollbar-track {
            background: transparent;
        }

        .scrollable::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.5);
            border-radius: 10px;
            border: 3px solid transparent;
        }

        .scrollable::-webkit-scrollbar-thumb:hover {
            background-color: rgba(107, 114, 128, 0.5);
        }


        .scrollable {
            scrollbar-width: thin;
            scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
        }

        .scrollable {
            scrollbar-width: thin;
            scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
        }


        .scrollable::-moz-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.5);
            border-radius: 10px;
            border: 3px solid transparent;
        }
    </style>
</head>

<body class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-lg">
        <h1 class="text-2xl font-bold mb-4 text-center">Message Board</h1>
        <?php
        echo $_SESSION["sql_output"];
        ?>
        <ul id="messageList" class="space-y-4 mb-6 scrollable">
            <?php
            $query = $conn->query("SELECT * FROM messages WHERE hidden = 0");

            if ($query->num_rows > 0) {
                while ($row = $query->fetch_assoc()) {
                    echo "<li class='bg-gray-100 p-4 rounded-lg shadow-sm'>{$row['message']}</li>";
                }
            } else {
                echo "<li class='text-gray-500'>No messages yet.</li>";
            }

            $conn->close();
            ?>
        </ul>
        <form method="post" action="postMessage.php" class="space-y-4">
            <div>
                <label for="m" class="block text-gray-700">Message</label>
                <input type="text" name="m" id="m" class="w-full p-2 border border-gray-300 rounded-lg mt-2">
            </div>
            <input type="submit" value="Post Message" class="w-full bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600 hover:cursor-pointer">
        </form>
    </div>
    <script>
        window.onload = function() {
            var messageList = document.getElementById('messageList');
            messageList.scrollTop = messageList.scrollHeight;
        };
    </script>
</body>

</html>