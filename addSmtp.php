<?php
session_start();

require 'database/database.php';
require 'objects/Smtp.php';


if (!isset($_SESSION["loggedin"]) || !$_SESSION["loggedin"]) {
    header("Location: index.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["smtp_settings"])) {

    $smtp_settings = $_POST["smtp_settings"];
    $host = isset($smtp_settings["host"]) ? $smtp_settings["host"] : "";
    $port = isset($smtp_settings["port"]) ? $smtp_settings["port"] : "";
    $username = isset($smtp_settings["username"]) ? $smtp_settings["username"] : "";
    $password = isset($smtp_settings["password"]) ? $smtp_settings["password"] : "";
    $encryption = isset($smtp_settings["encryption"]) ? $smtp_settings["encryption"] : "";

    $smtp = new SMTP($conn, $host, $port, $username, $password, $encryption);

    try {
        if ($smtp->create()) {
            echo "SMTP settings added successfully.";
        }
    } catch (PDOException $e) {
        echo "An error occurred while adding SMTP settings.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <?php include 'static_files/head.php'; ?>
<body>
    <?php include 'static_files/nav.php'; ?>

    <div class="container mt-4">
        <h1>Add SMTP Settings</h1>
        <form method="post">
            <div class="form-group">
                <label for="host">Host:</label>
                <input type="text" class="form-control" id="host" name="smtp_settings[host]" required>
            </div>
            <div class="form-group">
                <label for="port">Port:</label>
                <input type="text" class="form-control" id="port" name="smtp_settings[port]" required>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="smtp_settings[username]" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="smtp_settings[password]" required>
            </div>
            <div class="form-group">
                <label for="encryption">Encryption:</label>
                <select class="form-control" id="encryption" name="smtp_settings[encryption]">
                    <option value="none">None</option>
                    <option value="ssl">SSL</option>
                    <option value="tls">TLS</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add SMTP Settings</button>
        </form>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
