<?php
    require "db.php";
    session_start();
    if(!isset($_SESSION["userName"])){
        header("Location: login.php");
        die();
    }

    else if(isset($_POST["oldPassword"])){
        echo "oldPassword has been submitted";    
        if($_POST["newPassword1"] != $_POST["newPassword2"]){
            echo "The two new passwords are not equal try again";
        }

        else if(authenticateCustomer($_SESSION["userName"], $_POST["oldPassword"]) == 1){
            updateCustPassword($_SESSION["userName"], $_POST["oldPassword"], $_POST["newPassword1"]);
            session_destroy();
            header("Location: login.php");
            die();
        }
        else if(authenticateEmployee($_SESSION["userName"], $_POST["oldPassword"]) == 1){
            updateEmpPassword($_SESSION["userName"], $_POST["oldPassword"], $_POST["newPassword1"]);
            session_destroy();
            header("Location: login.php");
            die();
        }
        
        else{
            echo "Invalid old password try again";
        }

    }
?>
<!DOCTYPE html>
    <body>
        <h1>Create New Password</h1>
        
        <form method = "post">
            <label for="oldPassword">old password:</label><br>
            <input type="text" id="oldPassword" name="oldPassword" required><br>

            <label for="newPassword1">new password:</label><br>
            <input type="password" id="newPassword1" name="newPassword1" required><br>

            <label for="newPassword2">new password:</label><br>
            <input type="password" id="newPassword2" name="newPassword2" required><br>
            <p></p>
            <button class="button" type="submit" name="updatePassword">Update Password</button>
            <a href='store_main.php' class='button'>Home</a><br>
            
        </form>
    </body>
</html>