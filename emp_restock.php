<?php
session_start();
require "db.php";

if(!isset($_SESSION["emp_id"])){
    header("Location: login.php");
    die();
}

if(isset($_GET["id"]))
{
    $prod_id = $_GET['id'];
    $dbh = connectDB();
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        try{
            if($_POST['newstock'] > 0)
            {
            $dbh->beginTransaction();

            $newstock = $_POST['newstock'];
            $sql = "UPDATE products SET prod_actual = prod_actual + :newstock WHERE prod_id = :prod_id";
            $stmt = $dbh->prepare($sql);
            $stmt->execute(['newstock' => $newstock, 'prod_id' => $prod_id]);

            $sql = "INSERT INTO stock_change (changedate, prod_id, stockChange, emp_responsible) values (CURRENT_TIMESTAMP(), :prod_id, :stockChange, :emp_responsible)";
            $stmt = $dbh->prepare($sql);
            $stmt->execute(['prod_id' => $prod_id, 'stockChange' => $newstock, 'emp_responsible' => $_SESSION['emp_id']]);

            $dbh->commit();

            header("Location: emp_main.php");
            die();
            }
        } catch(Exception $e) {
            $dbh->rollBack();
            echo "Error" . $e->getMessage();
            exit;
        }
    }
    
}
    $sql = "SELECT * FROM products where prod_id = :prod_id";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(["prod_id"=> $prod_id]);
    $product = $stmt->fetch();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Restock Product</title>
    </head>
    <body>
        <h2>Restock Product: <?= $product['prod_name'] ?></h2>
        <form method="POST">
            <label for="newstock">Add Stock Amount:</label>
            <input type="number" id="newstock" name="newstock" required>
            <button type="submit">Add Stock</button>
        </form>
        <br><br>
        <a href="emp_main.php" class="button">Main Menu</a>
    </body>
</html>