<?php 
    require "db.php";
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $dbh = connectDB();
        $username = htmlspecialchars($_POST["userName"]);
        $password = htmlspecialchars($_POST["password"]);
        $firstName = htmlspecialchars($_POST["first_name"]);
        $lastName = htmlspecialchars($_POST["last_name"]);
        $email = htmlspecialchars($_POST["email"]);
        $streetAddress = htmlspecialchars($_POST["street_address"]);
        

        $sql = "SELECT COUNT(*) FROM customers WHERE cust_username = :user OR cust_email = :email";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(['user' => $username, 'email' => $email]);
        $exists = $stmt->fetch();

        if($exists[0] != 0)
        {
            echo "Error: User Already Exists";
            header("Location: registration.php");
            exit();
        } else {
            try{
                $dbh->beginTransaction();
                session_start();
                $_SESSION["userName"] = $username;
                addCustomer($username, $password, $firstName, $lastName, $email, $streetAddress);
                $dbh->commit();
        
                header("Location: store_main.php");
                echo "Welcome new customer";
                die();
            } catch(Exception $e) {
                $dbh->rollback();
                echo $e->getMessage();
                header("registration.php");
                exit();
            }
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Registration Form</title>
        <style>

        </style>
    </head>
    <body>
        <div class="container">
            <h1>User Registration Form</h1>
            <form action="registration.php" method="POST">
                <label for="userName">Username</label>
                <input type="text" id="userName" name="userName" required><br><br>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required><br><br>
                
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" required><br><br>

                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" required><br><br>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required><br><br>

                <label for="street_address">Street Address</label>
                <input type="text" id="street_address" name="street_address" required><br><br>

                <button type="submit">Register</button>
                <a href='store_main.php' class='button'>Cancel</a><br><br>
                
            </form>
        </div>
    </body>
</html>