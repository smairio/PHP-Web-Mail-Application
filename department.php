<?php
session_start();

require 'database/database.php';

require 'objects/Department.php';


if (!isset($_SESSION["loggedin"]) || !$_SESSION["loggedin"]) {
  
    exit();
}


$departments = Department::getAll($conn);
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'static_files/head.php'; ?>

<body>
    <?php include 'static_files/nav.php'; ?>

    <div class="container mt-4">
        <h1>Department List</h1>
      
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th>Department ID</th>
                    <th>Department Name</th>
                    <th>Select</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($departments as $department): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($department->getDepartmentID()); ?></td>
                        <td><?php echo htmlspecialchars($department->getDepartmentName()); ?></td>
                        <td><input type="checkbox" class="departmentCheckbox form-check-input" value="<?php echo $department->getDepartmentID(); ?>"></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

      
        <div>
            <button class="btn btn-primary" onclick="window.location.href='addDepartment.php'">Add</button>

            <button class="btn btn-primary" id="updateBtn" disabled>Update</button>

           
            <button class="btn btn-danger" id="deleteBtn" onclick="deleteDepartments()">Delete</button>
        </div>
    </div>

 
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
       
        var updateBtn = document.getElementById('updateBtn');
        var deleteBtn = document.getElementById('deleteBtn');
        var checkboxes = document.getElementsByClassName('departmentCheckbox');

       
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
        function deleteDepartments() {
            var result = confirm("Are you sure you want to delete selected Department(s)?");
            if (result) {
              
                var checkedIds = [];
                for (var i = 0; i < checkboxes.length; i++) {
                    if (checkboxes[i].checked) {
                        checkedIds.push(checkboxes[i].value);
                    }
                }
                
                window.location.href = 'deleteDepartments.php?ids=' + checkedIds.join(',');
            }
        }


        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].addEventListener('change', function() {
                var result = countCheckedCheckboxes();
                var count = result.count;
                var checkedId = result.checkedId;

                if (count >= 1) {
                  
                } else {
                   
                    deleteBtn.disabled = true;
                }

                if (count === 1) {
                
                    updateBtn.disabled = false;
                    updateBtn.onclick = function() {
                        window.location.href = 'updateDepartment.php?id=' + checkedId;
                    }
                } else {
                   
                    updateBtn.disabled = true;
                }
            });
        }
    </script>
</body>
</html>
