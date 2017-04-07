<?php

if ($_GET['id']) {
    $data = $newsletter->select('category','*','id = '.$_GET['id']);
    $data = $data[$_GET['id']];
    unlink('images/'.$data['logo']);
    unlink('images/'.$data['logo2']);
    unlink('images/'.$data['logo3']);
    $newsletter->delete('category','id = ' . $_GET['id']);
    header("location:index.php?p=category_list");
}
?>
