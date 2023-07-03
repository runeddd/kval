<?php
if(isset($_POST["login"]) && isset($_POST["pass"])){
    include_once("../db_connect.php");
    include_once("../functions.php");

    $query = "SELECT * FROM `users_todo` WHERE `login`='".$_POST["login"]."' AND `pass`='".$_POST["pass"]."'";
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
        array_push($arr_res, $row);
    }

    if(count($arr_res) == 0) exit("<p>Неправильный логин или пароль</p><meta http-equiv='refresh' content='3;URL=https://dev.rea.hrel.ru/ZKM/tdlist/login.php'/>");
    setcookie("login", $_POST["login"]);

    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Вход</title>
</head>
<body>
    <h1>Вход</h1>
    <br><br>
    <form action="login.php" method="post">
        <input type="text" name="login" placeholder="Login">
        <input type="password" name="pass" placeholder="Password">
        <button type="submit" name="SignIn" style="margin-left: 10px;">Войти</button>
    </form>
    <a href="register.php">Зарегистрироваться</a>
</body>
</html>