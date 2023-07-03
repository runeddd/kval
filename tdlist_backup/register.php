<?php
if(isset($_POST["login"]) && isset($_POST["pass"])){
    include_once("../db_connect.php");
    include_once("../functions.php");

    $query = "INSERT INTO `users_todo`(`login`, `pass`) VALUES ('".$_POST["login"]."','".$_POST["pass"]."')";
    $res_query = mysqli_query($connection,$query);

    if(!$res_query){
        echo "<p>Такой логин уже есть</p><meta http-equiv='refresh' content='3;URL=https://dev.rea.hrel.ru/ZKM/tdlist/register.php'/>";
        exit();
    }

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
    <title>Регистрация</title>
</head>
<body>
    <h1>Регистрация</h1>
    <br><br>
    <form action="register.php" method="post">
        <input type="text" name="login" placeholder="Login">
        <input type="password" name="pass" placeholder="Password">
        <button type="submit" name="SignIn" style="margin-left: 10px;">Зарегистрироваться</button>
    </form>
    <a href="login.php">Уже есть аккаунт</a>
</body>
</html>