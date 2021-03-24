<?php
// Σύνδεση στη βάση
require_once "php/config.php";

// Έναρξη session για έλεγχο αν υπάρχει χρήστης συνδεδεμένος
session_start();
 
// Αν δεν υπάρχει -> loginPage
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: loginPage.php");
    exit;
}

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
$user_id = $_SESSION['id'];
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    //Έλεγχος αν το όνομα χρήστη υπάρχει ήδη
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            $param_username = trim($_POST["username"]);
            
            if(mysqli_stmt_execute($stmt)){
    
                mysqli_stmt_store_result($stmt);
                
                //Αν υπάρχει -> σφάλμα
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    }
 
    //Έλεγχος κωδικού να είναι 8 και πάνω σύμβολα/γράμματα, έλεγχος γίνεται στην HTML
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 8){
        $password_err = "Must be at least 8 characters long and contain at least one number, one uppercase and lowercase letter and one special character.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    //Έλεγχος αν συμφωνούν τα πεδία του κωδικού
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }


    
    //Πριν γίνει καταχώρηση στη ΒΔ έλεχγος αν υπάρχουν σφάλματα
    if(empty($username_err) || (empty($password_err) && empty($confirm_password_err))){
    
        //Αλλαγή κωδικού μόνο
        if($username === "" && $password !== ""){
            $sql = "UPDATE users SET password = ? WHERE id = $user_id";

            if($stmt = mysqli_prepare($link, $sql)){

                mysqli_stmt_bind_param($stmt, "s", $param_password);
                
                $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
                
                if(mysqli_stmt_execute($stmt)){
                    //Με την αλλαγή -> logout -> loginPage
                    require_once "logout.php";
                    header("location: loginPage.php");
                } else{
                    echo "Something went wrong. Please try again later.";
                }

                mysqli_stmt_close($stmt);
            }
        }else if($password === "" && $username !== ""){//Αλλαγή ονόματος χρήστη μόνο
            $sql = "UPDATE users SET username = ? WHERE id = $user_id";
            
            if($stmt = mysqli_prepare($link, $sql)){
                
                mysqli_stmt_bind_param($stmt, "s", $param_username);
               
                $param_username = $username;
                
                if(mysqli_stmt_execute($stmt)){
                    // Με την αλλαγή -> logout -> loginPage
                    require_once "logout.php";
                    header("location: loginPage.php");
                } else{
                    echo "Something went wrong. Please try again later.";
                }

                mysqli_stmt_close($stmt);
            }
        }else if($username !== "" && $password !== ""){//αλλαγή και των δύο
            $sql = "UPDATE users SET username = ?,password = ? where id = $user_id";
            
            if($stmt = mysqli_prepare($link, $sql)){
            
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            if(mysqli_stmt_execute($stmt)){
                // Με την αλλαγή -> logout -> loginPage
                require_once "logout.php";
                header("location: loginPage.php");
            } else{
                echo "Something went wrong. Please try again later. Thank you!";
            }

            
            mysqli_stmt_close($stmt);
        }else if($username === "" && $password === ""){//σε περίπτωση που ο χρήστης πατήσει submit με άδεια πεδία
            echo "Please make an intput or go back to profile";
            header("location: userPage.php");
        }
    }
        
    }
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
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
            <a id="logout_btn" class="btn btn-danger" href="logout.php">Log Out</a>
        </div>


        <div class="form">
            <h2>Account Settings</h2>
            <p>(change username or password)</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                    <label>New Username</label>
                    <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                    <span class="help-block"><?php echo $username_err; ?></span>
                </div>    
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}" title="Must be at least 8 characters long and contain at least one number, one uppercase and lowercase letter and one special character" value="<?php echo $password; ?>">
                    <span class="help-block"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                    <span class="help-block"><?php echo $confirm_password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="userPage.php" class="button" style="color:rgb(48, 114, 236);">Back</a>
                </div>
            </form>
        </div>        
    </body>
</html>