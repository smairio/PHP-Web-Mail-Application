<?php

session_start();



require 'database/database.php';
require 'objects/Smtp.php'; 


if (!isset($_SESSION["loggedin"]) || !$_SESSION["loggedin"]) {
    
    header("Location: index.php");
    exit(); 
}


if (!isset($_GET["id"])) {
    header("Location: smtp.php");
    exit();
}

$smtp_id = $_GET["id"];


$smtp = SMTP::getById($conn, $smtp_id);


if (!$smtp) {
    header("Location: smtp.php");
    exit();
}


$error_message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["host"]) && isset($_POST["port"]) && isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["encryption"])) {
        $host = $_POST["host"];
        $port = $_POST["port"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        $encryption = $_POST["encryption"];

        try {
            
            $smtp->setHost($host);
            $smtp->setPort($port);
            $smtp->setUsername($username);
            $smtp->setPassword($password);
            $smtp->setEncryption($encryption);
            
            
            $smtp->update($conn);

           
            header("Location: smtp.php");
            exit();
        } catch (PDOException $e) {
          
            $error_message = "An error occurred while updating SMTP settings.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'static_files/head.php'; ?>

<body>
    <?php include 'static_files/nav.php'; ?>

    <div class="container mt-4">
        <h1>Update SMTP Settings</h1>
        <form method="post">
            <div class="form-group">
                <label for="host">Host:</label>
                <input type="text" class="form-control" id="host" name="host" value="<?php echo htmlspecialchars($smtp->getHost()); ?>" required>
            </div>
            <div class="form-group">
                <label for="port">Port:</label>
                <input type="text" class="form-control" id="port" name="port" value="<?php echo htmlspecialchars($smtp->getPort()); ?>" required>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($smtp->getUsername()); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($smtp->getPassword()); ?>" required>
            </div>
            <div class="form-group">
                <label for="encryption">Encryption:</label>
                <select class="form-control" id="encryption" name="encryption" required>
                    <option value="none" <?php if ($smtp->getEncryption() === "none") echo "selected"; ?>>None</option>
                    <option value="ssl" <?php if ($smtp->getEncryption() === "ssl") echo "selected"; ?>>SSL</option>
                    <option value="tls" <?php if ($smtp->getEncryption() === "tls") echo "selected"; ?>>TLS</option>
                </select>
            </div>
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <button type="submit" class="btn btn-primary">Update SMTP Settings</button>
        </form>
    </div>

  
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
