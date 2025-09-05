<?php
    require "common.php";
    require "db.php";
    session_start();
    if(!isset($_SESSION["userName"])){
        header("Location: login.php");
        die();
    }

    $products = getProducts();

?>
<!DOCTYPE html>
<html>
    <head>
        <style>
            h1{
                color: white;
                text-align: center;
                background-color: gray;
            }

            table, th, td 
            {
                border: 1px solid black;
            }
        </style>
        <title>Employee Main</title>
        
    </head>
    <body>
        <h1> Employee Main</h1><br>
        
        <a href='logout.php' class='button'>Logout</a><br>
        <a href='createPassword.php' class='button'>Change Password</a><br>
        <a href='store_main.php' class='button'>Home</a><br>
            
    <h3>Product List</h3>
    <table>
        <tr>
            <th>Product Name</th>
            <th>Price</th>
            <th>Stock Quantity</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?= $product['prod_name'] ?> </td>
                <td>$<?= $product['prod_price'] ?> </td>
                <td><?= $product['prod_actual'] ?> </td>
                <td>
                    <a href="emp_restock.php?id=<?= $product['prod_id'] ?>">Restock</a> |
                    <a href="emp_prodprice.php?id=<?= $product['prod_id'] ?>">Change Price</a> |
                    <a href="emp_stockhist.php?id=<?= $product['prod_id'] ?>">Stock History</a> |
                    <a href="emp_pricehist.php?id=<?= $product['prod_id'] ?>">Price History</a>
                    
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
            
    </body>
</html> 