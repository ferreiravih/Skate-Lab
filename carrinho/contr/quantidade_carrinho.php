<?php
session_start();

$total = 0;

if (isset($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $item) {
        $total += $item['quantidade'];
    }
}

echo $total;
