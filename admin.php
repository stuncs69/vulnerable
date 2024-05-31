<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] === false) {
    header("Location: validate.php");
    exit;
}

$servername = "localhost";
$username = "root";
$dbName = "messageboard";

$conn = new mysqli($servername, $username, "", $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['hide_message_id'])) {
        $messageId = $_POST['hide_message_id'];
        $stmt = $conn->prepare("UPDATE messages SET hidden = 1 WHERE id = ?");
        $stmt->bind_param('i', $messageId);
        $stmt->execute();
        $stmt->close();
        $feedback = "Message hidden successfully.";
    } elseif (isset($_POST['unhide_message_id'])) {
        $messageId = $_POST['unhide_message_id'];
        $stmt = $conn->prepare("UPDATE messages SET hidden = 0 WHERE id = ?");
        $stmt->bind_param('i', $messageId);
        $stmt->execute();
        $stmt->close();
        $feedback = "Message un-hidden successfully.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hide/Unhide Messages</title>
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

        .scrollable::-moz-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.5);
            border-radius: 10px;
            border: 3px solid transparent;
        }
    </style>
</head>

<body class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-lg">
        <h1 class="text-2xl font-bold mb-4 text-center">Manage Messages</h1>
        <?php
        if (isset($feedback)) {
            echo "<p class='text-green-500 text-center mb-4'>$feedback</p>";
        }
        ?>
        <form method="post" action="" class="space-y-4 mb-6">
            <div>
                <label for="hide_message_id" class="block text-gray-700">Select Message to Hide</label>
                <select name="hide_message_id" id="hide_message_id" class="w-full p-2 border border-gray-300 rounded-lg mt-2">
                    <?php
                    $query = $conn->query("SELECT id, message FROM messages WHERE hidden = 0");
                    if ($query->num_rows > 0) {
                        while ($row = $query->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['message']}</option>";
                        }
                    } else {
                        echo "<option value=''>No messages available</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600">Hide Message</button>
        </form>

        <form method="post" action="" class="space-y-4">
            <div>
                <label for="unhide_message_id" class="block text-gray-700">Select Message to Unhide</label>
                <select name="unhide_message_id" id="unhide_message_id" class="w-full p-2 border border-gray-300 rounded-lg mt-2">
                    <?php
                    $query = $conn->query("SELECT id, message FROM messages WHERE hidden = 1");
                    if ($query->num_rows > 0) {
                        while ($row = $query->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['message']}</option>";
                        }
                    } else {
                        echo "<option value=''>No hidden messages available</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600">Unhide Message</button>
        </form>
        <form method="post" action="index.php">
            <input type="submit" value="Back to message board" class="w-full text-blue-500 hover:cursor-pointer">
        </form>
    </div>
</body>

</html>
<?php
$conn->close();
?>
