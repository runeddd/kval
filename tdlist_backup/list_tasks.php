<?php
include_once("../db_connect.php");
include_once("../functions.php");

$date ="";
$filter="";
$orderby="";
if(isset($_POST["date"]) && $_POST["date"] != "") $date = " AND `date` >= '".$_POST["date"]."'";
if(isset($_POST["filter"])) $filter=$_POST["filter"];
if(isset($_POST["orderby"])) $orderby=$_POST["orderby"];



$query = "SELECT `id` FROM `users_todo` WHERE `login`='".$_COOKIE["login"]."'";
$res = mysqli_query($connection,$query);
$id = mysqli_fetch_assoc($res)["id"];

$query = "SELECT `id`,`task`,`completed`,`date`,`userid` FROM `tasks` WHERE `deleted`=false AND (userid=(SELECT `id` FROM `users_todo` WHERE `login`='".$_COOKIE["login"]."') OR `id` IN (SELECT `task_id` FROM todo_accesses WHERE `user_id`=(SELECT `id` FROM `users_todo` WHERE `login`='".$_COOKIE["login"]."'))) ".$filter.$date." ".$orderby;
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

    $arr_res = array();
    $rows = mysqli_num_rows($res_query);

    for ($i=0; $i < $rows; $i++){
        $row = mysqli_fetch_assoc($res_query);
        $checked = "";
        if($row["completed"] == "1") $checked = "checked";

        if($row["userid"] != $id) echo "<li style='height: 40px;'><input type='checkbox' ".$checked." disabled>".$row["task"]."</li>";
        else
            echo "<li><input type='checkbox' ".$checked." disabled>".$row["task"]." <a href='remove_task.php?index=".$row["id"]."'>Удалить</a><a href='edit_task.php?index=".$row["id"]."'>Редактировать</a>".$row["date"]."</li>";
    }
    exit;
?>