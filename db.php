<?php
    function connectDB()
    {
        try{
        $db_server = "mysql:host=classdb.it.mtu.edu;dbname=ndminor";
        $db_user = "ndminor";
        $db_pass = "123*Ndminor";
        $dbh = new PDO($db_server, $db_user, $db_pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e){
            echo "Connection failed:" . $e->getMessage();
            die();
        }
        return $dbh;
    }

    function authenticateCustomer( $user, $passwd )
    {
        try{
            $dbh = connectDB();
            $statement = $dbh->prepare("SELECT count(*) FROM customers where cust_username = :username and cust_password = sha2(:passwd,256) "); 
            $statement->bindParam(":username", $user);
            $statement->bindParam(":passwd", $passwd);
            $result = $statement->execute();
            $row = $statement -> fetch();
            $dbh = null;

            if($row[0] == 1){
                $dbh2 = connectDB();
                $statement2 = $dbh2->prepare("SELECT cust_id FROM customers where cust_username = :userName and cust_password = sha2(:passwod, 256)");
                $statement2->bindParam(":userName", $user);
                $statement2->bindParam(":passwod", $passwd);
                $result = $statement2->execute();
                $id = $statement2->fetch();
                $_SESSION["cust_id"] = $id[0];
            
                $dbh2 = null;
            }

            return $row[0];

        }catch(PDOException $e) 
        {
            print("CUST Error! " . $e->getMessage() . "<br/>");
            die();
        }
    }

    function authenticateEmployee($user, $passwd){
        try{
            $dbh = connectDB();
            $statement = $dbh->prepare("SELECT count(*) FROM employees where emp_username = :username and emp_password = sha2(:passwd,256) "); 
            $statement->bindParam(":username", $user);
            $statement->bindParam(":passwd", $passwd);
            $result = $statement->execute();
            $row = $statement -> fetch();
            $dbh = null;
            if($row[0] == 1){
                $dbh2 = connectDB();
                $statement2 = $dbh2->prepare("SELECT emp_id FROM employees where emp_username = :userName and emp_password = sha2(:passwod, 256)");
                $statement2->bindParam(":userName", $user);
                $statement2->bindParam(":passwod", $passwd);
                $result = $statement2->execute();
                $id = $statement2->fetch();
                $_SESSION["emp_id"] = $id[0];
            
                $dbh2 = null;
            }

            return $row[0];

        }catch(PDOException $e) 
        {
            print("EMP Error! " . $e->getMessage() . "<br/>");
            die();
        }
    }

    function get_orders($user)
    {
        
    }

    function addCustomer($user, $passwd, $firstname, $lastname, $email, $address){
        $dbh = connectDB();
        
        $statement = $dbh->prepare("INSERT INTO customers (cust_id, cust_username, cust_password, cust_fname, cust_lname, cust_email, cust_address) values (:cust_id, :username, sha2(:passwd, 256), :firstname, :lastname, :email, :addres)");
        $id = rand(0, 400);
        $statement->bindParam(":cust_id", $id);
        $statement->bindParam(":username", $user);
        $statement->bindParam(":passwd", $passwd);
        $statement->bindParam(":firstname", $firstname);
        $statement->bindParam(":lastname", $lastname);
        $statement->bindParam(":email", $email);
        $statement->bindParam(":addres", $address);

        $statement->execute();

    }

    function updateCustPassword($user, $oldPasswd, $newPasswd){
        echo "updating cust Password";
        $dbh = connectDB();
        $statement = $dbh->prepare("UPDATE customers SET cust_password = sha2(:newPasswd, 256) where cust_username = :username and cust_password = sha2(:oldPassword, 256)");
        $statement->bindParam(":newPasswd", $newPasswd);
        $statement->bindParam(":username", $user);
        $statement->bindParam(":oldPassword", $oldPasswd);
        

        $statement->execute();
    }

    function updateEmpPassword($user, $oldPasswd, $newPasswd){
        echo "updating emp";
        $dbh = connectDB();
        $statement = $dbh->prepare("UPDATE employees SET emp_password = sha2(:newPasswd, 256) where emp_username = :username and emp_password = sha2(:oldPassword, 256)");
        $statement->bindParam(":newPasswd", $newPasswd);
        $statement->bindParam(":oldPassword", $oldPasswd);
        $statement->bindParam(":username", $user);

        $statement->execute();
    }

?>