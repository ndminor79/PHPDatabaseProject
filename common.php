<?php
function getCategories()
{
    $dbh = connectDB();
    $sql = "SELECT * from categories";
    $stmt = $dbh->prepare($sql);
    return $stmt->fetchAll();
}

function getProducts()
{
    $dbh = connectDB();
    $sql = "SELECT * FROM products";
    $stmt = $dbh->query($sql);
    return $stmt->fetchAll();
}

function getProductsByCategory($category_name)
{
    $dbh = connectDB();
    $sql = "SELECT * FROM products WHERE prod_category = :category_name";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(['category_name' => $category_name]);
    return $stmt->fetchAll();
}
?>