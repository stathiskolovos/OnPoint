<?php
// Έναρξη session για έλεγχο αν υπάρχει χρήστης συνδεδεμένος
session_start();
 
// Αν υπάρχει ήδη συνδεδεμένος χρήστης ή ο admin οδήγησε στη σωστή σελίδα
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    if($_SESSION['username'] === "admin"){
        header("location: adminPage.php");
    }else{
        header("location: userPage.php");
    }
    
    exit;
}
 
// Σύνδεση με ΒΔ
require_once "php/config.php";
 
$lusername = $lpassword = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // έλεγχος στοιχείων που δόθηκαν στη φόρμα
    if(empty($username_err) && empty($password_err)){
        
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            $param_username = $username;
           
            if(mysqli_stmt_execute($stmt)){
               
                mysqli_stmt_store_result($stmt);
                
                // Αν υπάρχει ο χρήστης έλεγχος κωδικού
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // νέο session
                            session_start();
                            
                            // Δεδομένα χρήστη στο session
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            

                            if($username === "admin"){
                                  header("location: adminPage.php");
                            }else{
                                header("location: userPage.php");
                            }
                           
                        } else{
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    $username_err = "No account found with that username.";
                }
            } else{
                echo "Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    }
    
    // Διακοπή σύνδεσης
    mysqli_close($link);
}
?>