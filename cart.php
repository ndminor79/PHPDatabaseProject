<?php
session_start();
require "db.php";
$dbh = connectDB();

if(!isset($_SESSION["cart"]))
{
    $_SESSION["cart"] = [];
}

if($_SERVER["REQUEST_METHOD"] === 'POST')
{
    if(isset($_POST['add_to_cart']))
    {
        $prod_id = $_POST['prod_id'];
        $quantity = $_POST['quantity'];

        $sql = "SELECT * FROM products WHERE prod_id = :prod_id";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(["prod_id" => $prod_id]);
        $product = $stmt->fetch();

        $found = false;
        foreach($_SESSION['cart'] as $item)
        {
            if($item['prod_id'] == $prod_id && $quantity <= $product['prod_actual'])
            {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }

        if(!$found)
        {
            $sql = "SELECT * FROM products WHERE prod_id = :prod_id";
            $stmt = $dbh->prepare($sql);
            $stmt->execute(["prod_id" => $prod_id]);
            $product = $stmt->fetch();
            $_SESSION["cart"][] = ["prod_id" => $prod_id, "prod_name" => $product["prod_name"],"prod_price" => $product["prod_price"], "quantity"=> $quantity];
        }

        header("Location: cart.php");
        exit;
    }
        
    if(isset($_POST["removeItem"]))
    {
        $prod_id = $_POST["prod_id"];
        foreach($_SESSION["cart"] as $key => $item)
        {
            if($item["prod_id"] == $prod_id)
            {
                unset($_SESSION["cart"][ $key ]);
                break;
            }
        }

        $_SESSION["cart"] = array_values($_SESSION["cart"]);
        header("Location: cart.php");
        exit;
    }

    if(isset($_POST["updateQuantity"]))
    {
        $prod_id = $_POST["prod_id"];
        $newquantity = $_POST['newquantity'];
        $update = false;
        
        $sql = "SELECT * FROM products WHERE prod_id = :prod_id";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(["prod_id" => $prod_id]);
        $product = $stmt->fetch();

        foreach($_SESSION['cart'] as $item)
        {
            if($item['prod_id'] == $prod_id)
            {
                if($newquantity <= $product['prod_actual'])
                {
                    $item['quantity'] = $newquantity;
                    $update = true;
                    break;
                } else {
                    header("Location: cart.php?error=Invalid quantity");
                    exit;
                }
                
            }
        }

        if($update)
        {
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['prod_id'] == $prod_id) {
                    $item['quantity'] = $newquantity;
                    break;
                }
            }
            $_SESSION["cart"] = array_values($_SESSION["cart"]);
        }
        header("Location: cart.php");
        exit;
    }


    if(isset($_POST['checkout']))
    {
        try
        {
            $dbh->beginTransaction();

            $sql = "INSERT INTO orders (order_id, order_customer, order_date, order_status, order_total) VALUES (:ord_id, :ord_cust, CURRENT_TIMESTAMP(), 'Recieved', :grandtotal)";
            $stmt = $dbh->prepare($sql);
            $ord_id = rand(1000, 9999);
            $_SESSION["recentOrdId"] = $ord_id;
            $stmt->execute(["ord_id" => $ord_id, "ord_cust" => $_SESSION['cust_id'], "grandtotal" => $_POST["grandtotal"]]);

            $names = "";
            foreach($_SESSION['cart'] as $item)
            {
                $sql = "UPDATE products SET prod_actual = prod_actual - :quantity WHERE prod_id = :prod_id ";
                $stmt = $dbh->prepare($sql);
                $stmt->execute(["quantity" => $item["quantity"], "prod_id" => $item["prod_id"]]);

                $sql = "INSERT INTO stock_change (changedate, prod_id, stockChange, emp_responsible) values (CURRENT_TIMESTAMP(), :prod_id, :stockChange, :emp_responsible)";
                $stmt = $dbh->prepare($sql);
                $stmt->execute(['prod_id' => $item['prod_id'], "stockChange" => -$item["quantity"], "emp_responsible" => NULL]); 

                $names = $names . "<br>" . $item["prod_name"];
            }

            $stmt = $dbh->prepare("INSERT INTO orderitems (order_id, orit_prod_names) values (:id, :itemNames)");
            $stmt->bindParam(":id", $ord_id);
            $stmt->bindParam(":itemNames", $names);
            $stmt->execute();

            $dbh->commit();

            $_SESSION['cart'] = [];
            header("Location: checkout.php");
            exit;

        } catch(Exception $e) 
        {
            $dbh->rollBack();
            echo "Error" . $e->getMessage();
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Shopping Cart</title>
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
        <h2>Your Shopping Cart:</h2><br>
        <?php if(empty($_SESSION["cart"])): ?>
            <p>Your Cart is Empty!</p><br>
            <a href="store_main.php" class="button">Back to Store</a>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Current Quantity</th>
                        <th>Change Quantity</th>
                        <th>Item Total</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $grandtotal = 0;
                        foreach($_SESSION["cart"] as $item):
                            $subtotal = $item["prod_price"] * $item["quantity"];
                            $grandtotal += $subtotal;
                        
                    ?>
                    <tr>
                        <td><?= $item['prod_name'] ?></td>
                        <td>$<?= $item["prod_price"] ?></td>
                        <td><?= $item["quantity"] ?></td>
                        <td>
                            <form method="POST" action="cart.php" style="display:inline;">
                                <input type="hidden" name="prod_id" value="<?= $item["prod_id"] ?>">
                                <input type="number" name="newquantity" min="1" required>
                                <button type="submit" name="updateQuantity">Update Quantity</button>
                            </form>
                        </td>
                        <td>$<?= $subtotal ?></td>
                        <td>
                            <form method="POST" action="cart.php">
                                <input type="hidden" name="prod_id" value="<?= $item['prod_id'] ?>">
                                <button type="submit" name="removeItem">Remove Item</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h4>Grand Total = $<?= $grandtotal ?></h4>

        <form method="POST">
            <input type="hidden" name="grandtotal" value="<?= $grandtotal ?>">
            <button type="submit" name="checkout">Checkout</button>
        </form>
        <a href="store_main.php" class="button">Back to Store</a>
        <?php endif; ?>
    </body>
</html>