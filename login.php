<?php

require 'database/database.php';


if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]) {
  
    header("Location: index.php");
    exit(); 
}

$error_message = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['password']) && !empty($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

      
        $sql = "SELECT * FROM admin WHERE email = :email";
        
        if($stmt = $conn->prepare($sql)){
           
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            
         
            if($stmt->execute()){
               
                if($stmt->rowCount() == 1){                    
                   
                    $user = $stmt->fetch(PDO::FETCH_OBJ);
                    
                    if($password==$user->password){
                        
                        session_start();
                        
                       
                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $user->admin_id;
                        $_SESSION["email"] = $user->email; 
                        $_SESSION["first_name"] = $user->first_name;
                        $_SESSION["last_name"] = $user->last_name;
                     
                        header("location: index.php");
                    } else{
                    
                        $error_message = "The password you entered was not valid.";
                    }
                } else{
                  
                    $error_message = "No account found with that email.";
                }
            } else{
                $error_message = "Oops! Something went wrong. Please try again later.";
            }
        }
        
        
        unset($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/static_files/login.css">
</head>
<body>
    <form action="login.php" method="post">
        <h2>Login</h2>
        <input type="text" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" value="Login">
        <?php if(!empty($error_message)) { ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php } ?>
    </form>
</body>
</html>