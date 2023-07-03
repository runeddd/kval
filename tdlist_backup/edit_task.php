<?php
include_once("../db_connect.php");
include_once("../functions.php");

if(isset($_POST["task"]) && isset($_POST["completed"])){
    if($_POST["task"] == "" && $_POST["completed"]==""){
        header("Location: index.php");
        exit;    
    }
    $task = "";
    $complete = $_POST["completed"];
    if($_POST["task"] != ""){
        $task="`task`='".$_POST["task"]."'";
        $complete = ', '.$complete;
    }

    $query = "UPDATE `tasks` SET ".$task.$complete." WHERE `id`=".$_GET["index"]." AND userid=(SELECT `id` FROM `users_todo` WHERE `login`='".$_COOKIE["login"]."')";
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
}

$query = "SELECT `task` FROM `tasks` WHERE `deleted`=false AND id=".$_GET["index"];
$res_query = mysqli_query($connection,$query);

    if(!$res_query){
        echo ajax_echo(
            "Ошибка!", 
            "Ошибка в запросе!",
            true,
            "ERROR",
            null
        );
        exit();
    }

$row = mysqli_fetch_assoc($res_query);

if(isset($_POST["save"]) && count($_POST) > 0){
    $json = file_get_contents("https://dev.rea.hrel.ru/ZKM/?token=testtoken&type=list_todo_users&login=".$_COOKIE['login']);
    $users = json_decode($json)->other;

    for ($i=0; $i < count($users); $i++){
        if(isset($_POST[$users[$i]->login])){
            file_get_contents("https://dev.rea.hrel.ru/ZKM/?token=testtoken&type=add_access&login=".$users[$i]->login."&task=".$_GET["index"]);
        }
        else{
            file_get_contents("https://dev.rea.hrel.ru/ZKM/?token=testtoken&type=delete_access&login=".$users[$i]->login."&task=".$_GET["index"]);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Edit</title>
</head>
<body>
    <h1 style="font-size: 50px;text-align: left;margin-bottom: 10px;">Изменение задачи</h1>
    <form style="font-size: 35px;" action="edit_task.php?index=<?echo $_GET["index"]?>" method="post">
        <input type="text" name="task" placeholder="Новая формулировка...">
        <select name="completed" style="border-radius: 5px;margin-right: 10px;">
            <option value="" style="display: none;">Статус</option>
            <option value="`completed`=false">Не выполнена</option>
            <option value="`completed`=true">Выполнена</option>
        </select>
        <br>
        <button type="submit">Сохранить</button>
    </form>
    <form method="post" style="display: grid;" action="edit_task.php?index=<?echo $_GET["index"];?>">
        <br>
        <p style="margin-bottom: -2px;margin-top: -30px;">Предоставить доступ следующим пользователям:<button type="submit" style="margin-left: 5px" name='save'>Сохранить</button></p>
        <span id="tasks" style="margin-left: -3px;text-align: left;">
        </span>
    </form>
    <script>
        function showTasks() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
            var tasks = JSON.parse(this.responseText).other;
            var table = document.getElementById("tasks");
            for (var i = 0; i < tasks.length; i++) {
                var task = tasks[i];
                var input = document.createElement("input");

                input.type = "checkbox";
                input.name = task.login;
                input.id = task.login;

                var label = document.createElement("label");
                label.for = input.id;
                label.innerText = task.login;
                
                table.appendChild(input);
                table.appendChild(label);
                table.innerHTML = table.innerHTML+"<br>";

                
                
            }
            for (var i = 0; i < tasks.length; i++) {
                var task = tasks[i];
                <?$json = file_get_contents("https://dev.rea.hrel.ru/ZKM/?type=list_todo_users_task&taskid=".$_GET["index"]);
                $users = json_decode($json)->other;
                for ($i=0; $i < count($users); $i++){?>
                if(task.login == "<?echo $users[$i]->login;?>") document.getElementById(task.login).checked = true;
                <?}?>
            }
        }
        };
        xhttp.open("GET", "https://dev.rea.hrel.ru/ZKM/?type=list_todo_users&login=<?echo $_COOKIE["login"];?>", true);
        xhttp.send();
        }
        showTasks();
    </script>
</body>
</html>