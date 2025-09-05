<?php
session_start();
require 'db.php';

if(!isset($_SESSION['emp_id']))
{
   header("Location: login.php");
   die();
}

if(isset($_GET['id']))
{
    $prod_id = $_GET['id'];
    $dbh = connectDB();

    $sql = "SELECT * FROM products WHERE prod_id = :prod_id";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(["prod_id" => $prod_id ]);
    $product = $stmt->fetch();
    
    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        try{
            if($_POST['newprice'] > 0)
            {

            $dbh->beginTransaction();
            $newprice = $_POST['newprice'];

            $sql = "INSERT INTO productchange (changedate, changeditem, beforeprice, afterprice, employee_responsible) values (CURRENT_TIMESTAMP(), :prod_id, :oldprice, :newprice, :emp_responsible)";
            $stmt = $dbh->prepare($sql);
            $stmt->execute(['prod_id' => $prod_id, 'oldprice' => $product['prod_price'], 'newprice' => $newprice, 'emp_responsible' => $_SESSION['emp_id']]);

            $sql = "UPDATE products set prod_price = :newprice WHERE prod_id = :prod_id";
            $stmt = $dbh->prepare($sql);
            $stmt->execute(['newprice' => $newprice, 'prod_id' => $prod_id ]);

            $dbh->commit();

            header("Location: emp_main.php");
            die();
            } else {
                header("Location: emp_main.php");
            }
        } catch(Exception $e) {
            $dbh->rollback();
            echo "Error" . $e->getMessage();
            exit;
        }
    }

    
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Change Product Price</title>
    </head>
    <body>
        <h2>Change Price For Product: <?= $product['prod_name'] ?></h2>
        <form method="POST">
            <label for="newprice">New Price:</label>
            <input type="number" step="1" name="newprice" id="newprice" required>
            <button type="submit">Change Price</button>
        </form>
        <br><br>
        <a href="emp_main.php" class="button">Main Menu</a>
    </body>
</html>