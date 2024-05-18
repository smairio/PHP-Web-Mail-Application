<?php
require 'database/database.php';
require 'objects/Smtp.php';

if(isset($_GET['ids'])) {
    $smtpIDs = $_GET['ids'];

    $smtpIDs = explode(',', $smtpIDs);

    $deletedCount = Smtp::deleteSMTPs($conn, $smtpIDs);

    if($deletedCount !== false) {
        echo json_encode(["message" => "Successfully deleted $deletedCount smtp(s)."]);
    } else {
        echo json_encode(["message" => "Error occurred during deletion."]);
    }
} else {
    echo json_encode(["message" => "'ids' parameter is missing."]);
}
?>
