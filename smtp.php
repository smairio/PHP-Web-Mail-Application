<?php

session_start();


require 'database/database.php';


if (!isset($_SESSION["loggedin"]) || !$_SESSION["loggedin"]) {

    header("Location: index.php");
    exit(); 
}


$stmt = $conn->query('SELECT * FROM smtp');
$smtpConfigs = $stmt->fetchAll(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'static_files/head.php'; ?>

<body>
    <?php include 'static_files/nav.php'; ?>

    <div class="container mt-4">
        <h1>SMTP Configurations</h1>
        
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th>Configuration ID</th>
                    <th>Host</th>
                    <th>Port</th>
                    <th>Username</th>
                    <th>Encryption</th>
                    <th>Select</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($smtpConfigs as $config): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($config->id); ?></td>
                        <td><?php echo htmlspecialchars($config->host); ?></td>
                        <td><?php echo htmlspecialchars($config->port); ?></td>
                        <td><?php echo htmlspecialchars($config->username); ?></td>
                        <td><?php echo htmlspecialchars($config->encryption); ?></td>
                        <td><input type="checkbox" class="configCheckbox form-check-input" value="<?php echo $config->id; ?>"></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

     
        <div>
           
            <button class="btn btn-primary" onclick="window.location.href='addSmtp.php'">Add</button>

           
            <button class="btn btn-primary" id="updateBtn" disabled>Update</button>

            
            <button class="btn btn-danger" id="deleteBtn" onclick="deleteSMTPConfig()">Delete</button>
        </div>
    </div>

    
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        
        var updateBtn = document.getElementById('updateBtn');
        var deleteBtn = document.getElementById('deleteBtn');
        var checkboxes = document.getElementsByClassName('configCheckbox');

       
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

        
        function deleteSMTPConfig() {
            var result = confirm("Are you sure you want to delete selected SMTP configuration(s)?");
            if (result) {
               
                var checkedIds = [];
                for (var i = 0; i < checkboxes.length; i++) {
                    if (checkboxes[i].checked) {
                        checkedIds.push(checkboxes[i].value);
                    }
                }
                
                window.location.href = 'deleteSmtp.php?ids=' + checkedIds.join(',');
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
                        window.location.href = 'updateSmtp.php?id=' + checkedId;
                    }
                } else {
                 
                    updateBtn.disabled = true;
                }
            });
        }
    </script>
</body>
</html>
