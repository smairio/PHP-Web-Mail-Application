<?php

session_start();


require 'database/database.php';
require 'objects/Employe.php';
require 'objects/Department.php';


if (!isset($_SESSION["loggedin"]) || !$_SESSION["loggedin"]) {
   
    header("Location: index.php");
    exit(); 
}

$departments = Department::getAll($conn);


if (!isset($_GET["id"])) {
    header("Location: employee.php");
    exit();
}

$employee_id = $_GET["id"];


$employee = Employee::getById($conn, $employee_id);


if (!$employee) {
    header("Location: employee.php");
    exit();
}

$email_error = '';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["employee_name"]) && isset($_POST["email"]) && isset($_POST["department_id"])) {
        $employee_name = $_POST["employee_name"];
        $email = $_POST["email"];
        $department_id = $_POST["department_id"];

       
        // $existing_employee = Employee::getByEmail($conn, $email);
        // if ($existing_employee) {
           
        //     $email_error = "Email already exists. Please choose a different email.";
        // } else {
            
            $employee->setEmployeeName($employee_name);
            $employee->setEmail($email);
            $employee->setDepartmentID($department_id);
            $employee->update($conn);
            
            
            header("Location: employee.php");
            exit();
        // }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'static_files/head.php'; ?>

<body>
    <?php include 'static_files/nav.php'; ?>

    <div class="container mt-4">
        <h1>Update Employee</h1>
        <form method="post">
            <div class="form-group">
                <label for="employee_name">Employee Name:</label>
                <input type="text" class="form-control" id="employee_name" name="employee_name" value="<?php echo $employee->getEmployeeName(); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $employee->getEmail(); ?>" required>
                <span class="text-danger"><?php echo $email_error; ?></span>
            </div>
            <div class="form-group">
                <label for="department_id">Department:</label>
                <select class="form-control" id="department_id" name="department_id" required>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?php echo $department->getDepartmentID(); ?>" <?php if ($department->getDepartmentID() == $employee->getDepartmentID()) echo 'selected'; ?>><?php echo $department->getDepartmentName(); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Employee</button>
        </form>
    </div>

    
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
