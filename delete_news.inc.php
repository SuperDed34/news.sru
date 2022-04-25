<?php
    $del = $_GET['id'];
if(!$del){
    $errMsg="Непонятно";
    header("Location:news.php");
    exit;

}else{
    if(!$news->deleteNews($del)){
        $errMsg="Новости уже нет, обнови страницу";
    }else{
        header("Location:news.php");
        exit;
    }
}