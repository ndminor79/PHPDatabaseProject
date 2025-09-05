<?php
session_start();
session_destroy();
header("Location: store_main.php")
?>