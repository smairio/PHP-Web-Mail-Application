<?php

session_start();


require 'database/database.php';
require 'objects/Department.php';
require 'objects/Employe.php';
require 'objects/SMTP.php';


if (!isset($_SESSION["loggedin"]) || !$_SESSION["loggedin"]) {
   
    header("Location: login.php");
    exit(); 
}


$stmt = $conn->query('SELECT * FROM Department');
$departments = Department::getAll($conn);


$stmt = $conn->query('SELECT * FROM smtp');
$smtpConfigs = $stmt->fetchAll(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'static_files/head.php'; ?>

<body>
    <?php include 'static_files/nav.php'; ?>

    <div class="container mt-4">

        <form method="post" id="send-email-form" action="send.php">

            <div class="card mb-4">
                <div class="card-header">
                    <h2 class="card-title">Select SMTP Configuration</h2>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <?php foreach ($smtpConfigs as $config): ?>
                            <li class="list-group-item">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input smtp-radio" id="smtp_<?php echo $config->id; ?>" name="selected_smtp" value="<?php echo $config->id; ?>">
                                    <label class="form-check-label" for="smtp_<?php echo $config->id; ?>">
                                        <?php echo $config->host; ?>:<?php echo $config->port; ?> (<?php echo $config->username; ?>)
                                    </label>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <br>

                <div class="card-header">
                    <h2 class="card-title">Enter Email Content (HTML)</h2>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="email_subject">Email Subject:</label>
                        <input type="text" class="form-control" id="email_subject" name="email_subject">
                    </div>
                    <div class="form-group">
                        <label for="email_content">Email Content:</label>
                        <textarea class="form-control" id="email_content" name="email_content" rows="6"></textarea>
                    </div>
                </div>
                <br>

                <div class="card-header">
                    <h2 class="card-title">Select Employees</h2>
                </div>
                <div class="card-body">
                    <ul class="list-group mt-3" id="employee-list">
                        <?php foreach ($departments as $department): ?>
                            <li class="list-group-item">
                                <h5><?php echo $department->getDepartmentName(); ?></h5>
                                <?php
                                    $stmt = $conn->prepare('SELECT * FROM Employee WHERE DepartmentID = ?');
                                    $stmt->execute([$department->getDepartmentID()]);
                                    $employees = $stmt->fetchAll(PDO::FETCH_OBJ);
                                    foreach ($employees as $employee): ?>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input employee-checkbox" id="employee_<?php echo $employee->EmployeeID; ?>" name="selected_employees[]" value="<?php echo $employee->EmployeeID; ?>">
                                        <label class="form-check-label" for="employee_<?php echo $employee->EmployeeID; ?>">
                                            <?php echo $employee->EmployeeName; ?> (<?php echo $employee->Email; ?>)
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="submit" class="btn btn-primary mt-3" id="send-email-btn" disabled>Send Email</button>
                </div>
            </div>

            
            <div class="mt-4">
                <button id="select-all" class="btn btn-primary mr-2">Select All</button>
                <button id="deselect-all" class="btn btn-danger" disabled>Deselect All</button>
            </div>
        </form>
    </div>

   
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const employeeCheckboxes = document.querySelectorAll('.employee-checkbox');
            const selectAllButton = document.getElementById('select-all');
            const deselectAllButton = document.getElementById('deselect-all');
            const sendEmailBtn = document.getElementById('send-email-btn');
            const emailContentTextarea = document.getElementById('email_content');

            
            function anyChecked() {
                for (const checkbox of employeeCheckboxes) {
                    if (checkbox.checked) {
                        return true;
                    }
                }
                return false;
            }

            
            function updateSendButton() {
                sendEmailBtn.disabled = !anyChecked();
            }

           
            selectAllButton.addEventListener('click', function() {
                for (const checkbox of employeeCheckboxes) {
                    checkbox.checked = true;
                }
                deselectAllButton.disabled = false;
                updateSendButton();
            });

           
            deselectAllButton.addEventListener('click', function() {
                for (const checkbox of employeeCheckboxes) {
                    checkbox.checked = false;
                }
                deselectAllButton.disabled = true;
                updateSendButton();
            });

          
            for (const checkbox of employeeCheckboxes) {
                checkbox.addEventListener('change', updateSendButton);
            }

            
            emailContentTextarea.addEventListener('input', function() {
                updateSendButton();
            });

            const filterButton = document.getElementById('filter-btn');
            const departmentSelect = document.getElementById('department_id');
            const employeeList = document.getElementById('employee-list');

            filterButton.addEventListener('click', function() {
                const departmentId = departmentSelect.value;
               
                fetch('send.php', {
                    method: 'POST',
                    body: JSON.stringify({ department_id: departmentId }),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    
                    employeeList.innerHTML = data.employeeListHtml;
                    
                })
                .catch(error => console.error('Error:', error));
            });
        });
    </script>

</body>
</html>
