<?php
include_once("../db_connect.php");
include_once("../functions.php");

$query = "INSERT INTO `tasks`(`task`,`userid`) VALUES ('".$_POST['task']."',(SELECT `id` FROM `users_todo` WHERE `login`='".$_COOKIE["login"]."'))";
    $res_query = mysqli_query($connection,$query);

    if(!$res_query){
        echo ajax_echo(
            "Ошибка!", 
            "Ошибка в запросе!",
            true,
            "ERROR",
            null
        );
        exit($query);
    }
header("Location: index.php");
exit;
?>
