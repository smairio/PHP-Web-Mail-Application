<?php

session_start();


require 'database/database.php';


if (!isset($_SESSION["loggedin"]) || !$_SESSION["loggedin"]) {
   
    header("Location: index.php");
    exit();
}



$stmt = $conn->query('SELECT * FROM Employee');
$employees = $stmt->fetchAll(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="en">
    <?php
     include 'static_files/head.php';
    ?>
<body>
    <?php
     include 'static_files/nav.php';
    ?>

    <div class="container mt-4">
        <h1>Employee List</h1>
       
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th>Employee ID</th>
                    <th>Employee Name</th>
                    <th>Email</th>
                    <th>Department Name</th>
                    <th>Select</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $employee): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($employee->EmployeeID); ?></td>
                        <td><?php echo htmlspecialchars($employee->EmployeeName); ?></td>
                        <td><?php echo htmlspecialchars($employee->Email); ?></td>
                        <td>
                            <?php
                           
                            $stmt = $conn->prepare('SELECT DepartmentName FROM Department WHERE DepartmentID = ?');
                            $stmt->execute([$employee->DepartmentID]);
                            $department = $stmt->fetch(PDO::FETCH_OBJ);
                            echo $department->DepartmentName;
                            ?>
                        </td>
                        <td><input type="checkbox" class="employeeCheckbox form-check-input" value="<?php echo $employee->EmployeeID; ?>"></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

       
        <div>
            
            <button class="btn btn-primary" onclick="window.location.href='addEmploye.php'">Add</button>

            
            <button class="btn btn-primary" id="updateBtn" disabled>Update</button>

           
            <button class="btn btn-danger" id="deleteBtn" onclick="deleteEmployee()">Delete</button>
        </div>
    </div>

   
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
       
        var updateBtn = document.getElementById('updateBtn');
        var deleteBtn = document.getElementById('deleteBtn');
        var checkboxes = document.getElementsByClassName('employeeCheckbox');

        
        function countCheckedCheckboxes() {
            var count = 0;
            var checkedId = null;
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    count++;
                    checkedId = checkboxes[i].value;
                }
            }
            return { count: count, checkedId: checkedId };
        }

        
        function deleteEmployee() {
            var result = confirm("Are you sure you want to delete selected employee(s)?");
            if (result) {
                // Perform deletion logic here
                var checkedIds = [];
                for (var i = 0; i < checkboxes.length; i++) {
                    if (checkboxes[i].checked) {
                        checkedIds.push(checkboxes[i].value);
                    }
                }
                
                window.location.href = 'deleteEmployee.php?ids=' + checkedIds.join(',');
            }
            }       
            for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].addEventListener('change', function() {
                var result = countCheckedCheckboxes();
                var count = result.count;
                var checkedId = result.checkedId;

                if (count >= 1) {
                   
                    deleteBtn.disabled = false;
                } else {
                    
                    deleteBtn.disabled = true;
                }

                if (count === 1) {
                 
                    updateBtn.disabled = false;
                    updateBtn.onclick = function() {
                        window.location.href = 'updateEmployee.php?id=' + checkedId;
                    }
                } else {
                    
                    updateBtn.disabled = true;
                }
            });
        }
    </script>
</body>
</html>
