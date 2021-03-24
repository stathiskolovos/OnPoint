<?php
// Έναρξη session για έλεγχο αν υπάρχει χρήστης συνδεδεμένος
session_start();
 
// Αν δεν υπάρχει -> loginPage
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: userPage.php");
    exit;
}
 
// Σύνδεση στη βάση
require_once "php/config.php";
 
$username = $password = "";
$username_err = $password_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    //  if username empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // if password  empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    if(empty($username_err) && empty($password_err)){
    
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
           
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            $param_username = $username;
            
            if(mysqli_stmt_execute($stmt)){

                mysqli_stmt_store_result($stmt);
                
                //Αν υπάρχει ο χρήστης τσέκαρε κωδικό
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Αν είναι σωστός ο κωδικός -> νέο session
                            session_start();
                            
                            // δεδομένα χρήστη στο session
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            if($username === "admin"){
                                // αν το username admin -> adminPage
                                header("location: adminPage.php");
                            }else{
                                // αλλιώς σελίδα χρήστη
                                header("location: userPage.php");
                            }
                        } else{
                            // αν δεν είναι σωστός error
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // αν δεν υπάρχει ο χρήστης error
                    $username_err = "No account found with that username.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    }
    
    // Διακοπή σύνδεσης
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/forms.css">
    <link rel="stylesheet" type="text/css" href="css/site_header.css">

</head>
<body style="background-color: rgba(221, 218, 218, 0.726);">

    <div class="header_box">
        <div>
            <img class="header_logo" src="css/icons/logo2.png">
        </div>

        <h class="header_title">OnPoint</h>
    </div>

    <div class="form">

        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>" >
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>" style>
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                 <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="signUPPage.php">Sign up now</a></p>
            </form>

        </div>
</body>
</html>