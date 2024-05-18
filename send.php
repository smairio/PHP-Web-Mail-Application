<?php

session_start();


require 'database/database.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if (!isset($_SESSION["loggedin"]) || !$_SESSION["loggedin"]) {
  
    header("Location: login.php");
    exit(); 
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    if (isset($_POST["selected_smtp"]) && isset($_POST["email_subject"]) && isset($_POST["email_content"]) && isset($_POST["selected_employees"])) {
      
        $smtpId = $_POST["selected_smtp"];
       
        $emailSubject = $_POST["email_subject"];
        $emailContent = $_POST["email_content"];


        $selectedEmployees = $_POST["selected_employees"];

        
        $smtpStmt = $conn->prepare('SELECT * FROM smtp WHERE id = ?');
        $smtpStmt->execute([$smtpId]);
        $smtpConfig = $smtpStmt->fetch(PDO::FETCH_OBJ);

        require 'PHPMailer/src/Exception.php';
        require 'PHPMailer/src/PHPMailer.php';
        require 'PHPMailer/src/SMTP.php';

        
        foreach ($selectedEmployees as $employeeId) {
            
            $mail = new PHPMailer(true);
            $mail->IsSMTP();                          



            try {
                
                $mail->Host = trim($smtpConfig->host);
                $mail->Port = $smtpConfig->port;
                $mail->SMTPAuth = true;
                $mail->Username = trim($smtpConfig->username);
                $mail->Password = trim($smtpConfig->password);
                $mail->SMTPSecure = strtolower(trim($smtpConfig->encryption));

              
                $employeeStmt = $conn->prepare('SELECT * FROM Employee WHERE EmployeeID = ?');
                $employeeStmt->execute([$employeeId]);
                $employee = $employeeStmt->fetch(PDO::FETCH_OBJ);

              
                if (!$employee) {
                    throw new Exception("Employee with ID {$employeeId} not found.");
                }

               
                $mail->setFrom($smtpConfig->username);
                $mail->isHTML(true);
                $mail->Subject = $emailSubject;
                $mail->Body = str_replace(['[Date]','[Recipient Name]', '[Your Name]'], [date('Y-m-d'), $employee->EmployeeName, trim($smtpConfig->username)], $emailContent);

             
                $mail->addAddress(trim($employee->Email));

             
                if (!$mail->send()) {
                    throw new Exception("Failed to send email to {$employee->Email}. Mailer Error: " . $mail->ErrorInfo);
                }

               
                echo "Email sent successfully to {$employee->Email}!<br>";
            } catch (Exception $e) {
                
                echo "Failed to send email to {$employee->Email}. Error: " . $e->getMessage() . "<br>";
            }
        }
    } else {
        echo "All fields are required!";
    }
}
?>
