<?php
session_start();

require 'database/database.php';
require 'objects/Employe.php';

if (!isset($_SESSION["loggedin"]) || !$_SESSION["loggedin"]) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['ids']) && !empty($_GET['ids'])) {
    $ids = explode(',', $_GET['ids']);

    try {
        Employee::deleteEmployees($conn, $ids);

        header("Location: employee.php");
        exit();
    } catch (PDOException $e) {
        echo "An error occurred while deleting employees: " . $e->getMessage();
    }
} else {
    header("Location: employee.php");
    exit();
}
?>
