<?php


$page = intval($_GET['page']);


try {
    $content = file_get_contents('produk/produk_page_' . $page . '.json');

    echo $content;
    
} catch (Exception $error) {
    echo "Error: {$error->getMessage()}";
}
