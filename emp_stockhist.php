<?php
session_start();
require "db.php";

#if(!isset($_SESSION["emp_id"]))
#{
#    header("Location: emp_main.php");
#    die();
#}

function getStockHist($prod_id)
{
    $dbh = connectDB();
    $sql = "SELECT * FROM stock_change WHERE prod_id = :prod_id ORDER BY changedate DESC";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(["prod_id" => $prod_id]);
    return $stmt->fetchAll();
}

if(isset($_GET["id"]))
{
    $dbh = connectDB();

    $prod_id = $_GET["id"];
    $history = getStockHist($prod_id);

    $sql = "SELECT * FROM products WHERE prod_id = :prod_id";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(["prod_id" => $prod_id ]);
    $product = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Stock History</title>
        <style>
            table, th, td 
            {
                border: 1px solid black;
            }
        </style>
    </head>
    <body>
        <h2>Stock History: <?= $product['prod_name'] ?></h2>
        <table>
            <tr>
                <th>Timestamp</th>
                <th>Updated Stock</th>
                <th>Employee Responsible</th>
            </tr>
            <?php foreach($history as $log): ?>
                <tr>
                    <td><?= $log['changedate'] ?></td>
                    <td><?= $log['stockChange'] ?></td>
                    <td><?= $log['emp_responsible'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <br><br>
        <a href="emp_main.php" class="button">Main Menu</a>
    </body>
</html>