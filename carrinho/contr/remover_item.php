<?php
session_start(); 

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    if (isset($_SESSION['carrinho'][$id])) {
        unset($_SESSION['carrinho'][$id]);
    }
}

header("Location: ../carrinho.php");
exit;