# Message Board

## Overview

This PHP-based message board application is vulnerable to several common security issues, including Cross-Site Scripting (XSS), SQL Injection (SQLi), and hardcoded credentials. This document outlines these vulnerabilities, where they occur, and suggests remediation steps to secure the application.

## Vulnerabilities

### 1. Cross-Site Scripting (XSS)

**Location:** `index.php`

#### Code:
```php
while ($row = $query->fetch_assoc()) {
    echo "<li class='bg-gray-100 p-4 rounded-lg shadow-sm'>{$row['message']}</li>";
}
```

#### Explanation:
The code directly outputs user-submitted messages to the page without sanitizing the content. An attacker could inject malicious JavaScript code into a message, which would be executed in the context of other users' browsers when they view the message.

#### Remediation:
Escape the output to prevent script injection. Use `htmlspecialchars` to convert special characters to HTML entities.

```php
while ($row = $query->fetch_assoc()) {
    $safe_message = htmlspecialchars($row['message'], ENT_QUOTES, 'UTF-8');
    echo "<li class='bg-gray-100 p-4 rounded-lg shadow-sm'>{$safe_message}</li>";
}
```

### 2. SQL Injection (SQLi)

**Location:** `postMessage.php`

#### Code:
```php
$message = $_POST['m'];
$query = "INSERT INTO `messages` (`message`) VALUES ('$message');";
```

#### Explanation:
The code constructs an SQL query by directly embedding user input into the query string. This allows an attacker to inject malicious SQL code, potentially compromising the database.

#### Remediation:
Use prepared statements with parameterized queries to safely handle user input.

```php
if (isset($_POST["m"]) && $_POST["m"] != "") {
    $message = $_POST['m'];
    $stmt = $conn->prepare("INSERT INTO messages (message) VALUES (?)");
    $stmt->bind_param("s", $message);
    $stmt->execute();
    $stmt->close();
}
```

### 3. Hardcoded Credentials

**Location:** All PHP files (e.g., `index.php`, `postMessage.php`, `validate.php`)

#### Code:
```php
$servername = "localhost";
$username = "root";
$password = ""; // change these credentials as you wish
$dbName = "messageboard";
```

#### Explanation:
Hardcoding credentials in the source code is a security risk, as it exposes sensitive information. If the code is accidentally leaked or accessed by unauthorized individuals, they could gain access to the database.

#### Remediation:
Store credentials securely using environment variables or configuration files outside the web root.

Example using environment variables:
```php
$servername = getenv('DB_SERVER');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');
$dbName = getenv('DB_NAME');
```

Set environment variables in the server configuration or a `.env` file (ensure this file is not accessible via the web server).

## Secure Configuration

1. **Environment Variables:**
   Set environment variables for database credentials.
   ```bash
   export DB_SERVER="localhost"
   export DB_USERNAME="root"
   export DB_PASSWORD=""
   export DB_NAME="messageboard"
   ```

2. **Sanitize User Input:**
   Always sanitize and validate user inputs to prevent XSS and SQLi attacks.

3. **Use Prepared Statements:**
   Utilize prepared statements for all database interactions involving user input.

4. **Escape Output:**
   Escape all output to prevent XSS attacks using functions like `htmlspecialchars`.

5. **Secure Session Handling:**
   Implement secure session handling mechanisms, including secure cookie flags and session regeneration upon login.

By addressing these vulnerabilities, the message board application can be made significantly more secure against common web application attacks.