<?php
session_start();

require 'database/database.php';

require 'objects/Department.php';

if (!isset($_SESSION["loggedin"]) || !$_SESSION["loggedin"]) {
    header("Location: index.php");
    exit(); 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["department_name"])) {
        $department_name = $_POST["department_name"];
        $department = new Department($conn, $department_name);
        try {
            if ($department->create()) {
                header("Location: department.php");
                exit();
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error_message = "Department name already exists. Please choose a different name.";
            } else {
                $error_message = "An error occurred while adding the department. Please try again later.";
            }
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
        <h1>Add Department</h1>
        <form method="post">
            <div class="form-group">
                <label for="department_name">Department Name:</label>
                <input type="text" class="form-control" id="department_name" name="department_name" required>
                <?php if (isset($error_message)): ?>
                <div class="alert alert-danger mt-2" role="alert">
                    <?php echo $error_message; ?>
                </div>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Add Department</button>
        </form>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
