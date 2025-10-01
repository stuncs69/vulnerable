<?php
session_start();

$servername = $_ENV['DB_HOST'] ?? "localhost";
$dbUsername = $_ENV['DB_USER'] ?? "root";
$dbPassword = $_ENV['DB_PASSWORD'] ?? "";
$dbName = $_ENV['DB_NAME'] ?? "messageboard";

$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    echo "You are already logged in.";
    header("Location: admin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $posted_username = $_POST['username'] ?? '';
    $posted_password = $_POST['password'] ?? '';

    // SQL Injection prone query
    $sql = "SELECT * FROM users WHERE username = '$posted_username' AND password = '$posted_password'";

    try {
        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception($conn->error);
        }

        if ($result->num_rows > 0) {
            $_SESSION['loggedin'] = true;
            echo "Login successful.";
            header("Location: admin.php");
            exit;
        } else {
            $login_error = "Invalid username or password.";
        }
    } catch (Exception $e) {
        $login_error = "SQL Error: " . $e->getMessage();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
    <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold mb-4 text-center">Login</h1>
        <?php
        if (isset($login_error)) {
            echo "<p class='text-red-500 text-center mb-4'>$login_error</p>";
        }
        ?>
        <form method="POST" action="" class="space-y-4">
            <div>
                <label for="username" class="block text-gray-700">Username</label>
                <input type="text" id="username" name="username" class="w-full p-2 border border-gray-300 rounded-lg mt-2" required>
            </div>
            <div>
                <label for="password" class="block text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="w-full p-2 border border-gray-300 rounded-lg mt-2" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600">Login</button>
        </form>
    </div>
</body>

</html>
