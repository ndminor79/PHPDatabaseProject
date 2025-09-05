<?php
    session_start();
    require "db.php";
    if(isset($_POST["userName"])){
        $username = htmlspecialchars($_POST["userName"]);
        $passwd = htmlspecialchars($_POST["password"]);
        if(authenticateCustomer($username, $passwd) == 1){
            $_SESSION["userName"] = $username;
            header("Location: store_main.php");
            die();
        }
        else if(authenticateEmployee($username, $passwd) == 1){
            $_SESSION["userName"] = $username;

            if ($passwd == "default123"){
                header("Location: createPassword.php");
                die();
            }
            header("Location: emp_main.php");
            die();
        }
        else{
            echo "Invalid username or password try again";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <style>
            .button{
                border:none;
                color: blue;
                padding: 15px 32px;
                text-align:center;
                text-decoration:none;
                display: inline-block;
                font-size: 16px;
                margin: 4px 2px;
                cursor:pointer;
            }
        </style>

    </head>
    <body>
        <form method="post">
            <label for="userName">username:</label><br>
            <input type="text" id="userName" name="userName" required><br>
            <label for="password">password:</label><br>
            <input type="password" id="password" name="password" required><br>
            <p></p>
            <button class="button" type="submit" name="login">Login</button>
            <?php 
                
            ?>
        </form>

</html>