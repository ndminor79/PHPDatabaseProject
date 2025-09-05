<?php
    session_start();
    if(!isset($_SESSION["userName"])){
        header("Location: login.php");
        die();
    }
    
?>
<!DOCTYPE html>
    <body>
        <!-- This is a comment -->
        <a href='createPassword.php' class='button'>Change Password</a><br>
        <a href='viewOrders.php' class='button'>View Orders</a><br>
        <a href='store_main.php' class='button'>Home</a><br>
    </body>
</html>