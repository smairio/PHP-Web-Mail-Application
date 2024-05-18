<?php
require 'database/database.php';

require 'objects/Department.php';

if(isset($_GET['ids'])) {
    $departmentIDs = $_GET['ids'];

    $departmentIDs = explode(',', $departmentIDs);

    $deletedCount = Department::deleteDepartments($conn, $departmentIDs);

    if($deletedCount !== false) {
        echo json_encode(["message" => "Successfully deleted $deletedCount department(s)."]);
    } else {
        echo json_encode(["message" => "Error occurred during deletion."]);
    }
} else {
    echo json_encode(["message" => "'ids' parameter is missing."]);
}
?>
