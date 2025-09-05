<?php 
session_start();

if(!isset($_SESSION["cart"]))
{
    $_SESSION["cart"] = [];
}

function prodInCart($prod_id)
{
    foreach($_SESSION["cart"] as $item)
    {
        if($item["prod_id"] == $prod_id)
        {
            return true;
        }
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Store Main Menu</title>
        <style>
            h1,h2{
                background-color: blueviolet;
                color: white;
                text-align: center;
            }

            h3{
                letter-spacing: 2px;
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
        <header>
            <div class="title">
                <h1>JSNM Online Store</h1>
            </div>
            <div class="auth_buttons">
                <?php
                     if(isset($_SESSION["userName"])){
                        echo "<a href='cart.php' class='button'>View Cart</a>";
                        echo "<a href='logout.php' class='button'>Logout</a>";
                        echo "<a href='cust_main' class='button'>Account Settings</a>";

                     } else{
                        echo "<a href='login.php' class='button'>Login</a>";
                        echo "<a href='registration.php' class='button'>Register</a>";
                     }
                ?>
            </div>
        </header>

        <div class="categories">
        <br>
            <h2>Categories</h2><br><br>
            <div class="category_list">
                <h3>Electronics</h3>
                <p>Laptops, Phones, Ect.</p>
                <?php
                    echo "<a href='electronics_products.php' class='button'>View</a>";
                ?><br><br>
                
                <h3>Clothing</h3>
                <p>Shirts, Jeans, and Jackets</p>
                <?php
                    echo "<a href='clothing_products.php' class='button'>View</a>";
                ?><br><br>

                <h3>Food</h3>
                <p>Fruits, Vegetables, and Snacks</p>
                <?php
                    echo "<a href='food_products.php' class='button'>View</a>";
                ?><br><br>

                <h3>Home</h3>
                <p>Furniture and Appliances</p>
                <?php
                    echo "<a href='home_products.php' class='button'>View</a>";
                ?><br><br>
            </div>
        </div>
        <footer>
            <p>&copy; 2024 JSNM Online Store. All rights reserved.</p>
        </footer>            
    </body>
</html>