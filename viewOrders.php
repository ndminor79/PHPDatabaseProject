<?php
    require "db.php";
    session_start();
    if(!isset($_SESSION["userName"])){
        header("Location: login.php");
        die();
    }

    $dbh = connectDB();
    $statement = $dbh->prepare("SELECT * from orders where order_customer = :customer order by order_date desc");
    $statement->bindParam(":customer", $_SESSION["cust_id"]);
    $result = $statement->execute();
    $orders = $statement->fetchAll();

    
?>
<!DOCTYPE html>
<body>
    <a href="cust_main" class="button">Return to Settings</a>
    <h1>Orders:</h1><br><br>
    <?php foreach ($orders as $order):?>
    <p>-------------------------------------------------------------------------------------------------------</p>
    <h2>Order Number: <?php echo $order[0];?></h2><br>
    <h4>Date: <?php echo $order[2]?></h4><br>
    <h4>Status: <?php echo $order[3]?></h4><br>
    <h4>Total: $<?php echo $order[4]?></h4><br>
    <h4>Items: <?php
    $statement = $dbh->prepare("SELECT orit_prod_names from orderitems where order_id = :ord_id");
    $statement->bindParam(":ord_id", $order[0]);
    $result = $statement->execute();
    $Items = $statement->fetch();
    echo $Items[0];
    ?></h4><br>

    <?php endforeach; ?>
</body>
</html>