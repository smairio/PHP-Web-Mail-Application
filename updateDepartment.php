<?php

session_start();


require 'database/database.php';
require 'objects/Department.php';


if (!isset($_SESSION["loggedin"]) || !$_SESSION["loggedin"]) {
    
    header("Location: index.php");
    exit(); 
}


if (!isset($_GET["id"])) {
    header("Location: department.php");
    exit();
}

$department_id = $_GET["id"];


$department = Department::getById($conn, $department_id);


if (!$department) {
    header("Location: department.php");
    exit();
}


$error_message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["department_name"])) {
        $department_name = $_POST["department_name"];
        try {
            $department->setDepartmentName($department_name);
            $department->update($conn);
            
            header("Location: department.php");
            exit();
        } catch (PDOException $e) {
            
            $error_message = "Error: Department name '$department_name' already exists. Please choose a different name.";
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
        <h1>Update Department</h1>
        <form method="post">
            <div class="form-group">
                <label for="department_name">Department Name:</label>
                <input type="text" class="form-control" id="department_name" name="department_name" value="<?php echo htmlspecialchars($department->getDepartmentName()); ?>" required>
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger mt-2"><?php echo $error_message; ?></div>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Update Department</button>
        </form>
    </div>

  
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
