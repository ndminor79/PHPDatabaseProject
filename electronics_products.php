<?php
    session_start();
    require "common.php";
    require "db.php";
    $elecproducts = getProductsByCategory("Electronics");
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Electronics</title>
        <style>
            table, th, td 
            {
                border: 1px solid black;
            }

            a{
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
        <h1>Electronics</h1><br><br>

        <a href="store_main.php" class="button">Store Main</a><br><br>

        <h3>Product List</h3>
        <table>
        <tr>
            <th>Image</th>
            <th>Product Name</th>
            <th>Price</th>
            <th>Stock Quantity</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($elecproducts as $product): ?>
            <tr>
                <td><img src=<?= $product['prod_image'] ?> width="50" height="50"></td>
                <td><?= $product['prod_name'] ?> </td>
                <td>$<?= $product['prod_price'] ?> </td>
                <td><?= $product['prod_actual'] ?> </td>
                <td>
                    <?php if(isset($_SESSION["userName"])): ?>
                    <form method="POST" action="cart.php">
                        <input type="hidden" name="prod_id" value="<?= $product['prod_id'] ?>">
                        <input type="number" name="quantity" value="1" min="1" max="<?= $product['prod_actual'] ?>" required>
                        <button type="submit" name="add_to_cart">Add to Cart</button>
                    </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </table>
    </body>
</html>