<?php
if(isset($_GET["logout"])){
    setcookie("login", null, time()-3600);
    header("Location: login.php");
}
if(!isset($_COOKIE["login"])){
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div style="margin-top: 10px;text-align: right;">
        <h3 style="font-size: 12px;margin-right: -6px;">Пользователь: <?echo $_COOKIE["login"]?></h3>
        <a href="index.php?logout">Выйти</a>
    </div>
    <h1 style="margin-top: 10px;text-align: left;font-size: 2.5em;">To-Do List</h1>
    <form action="add_task.php" method="post" style="margin-top: -15px;">
        <input type="text" name="task" placeholder="Название задания">
        <button type="submit" name="add">Добавить</button>
    </form>
    <form action="index.php" method="post">
        <select name="orderby" style="margin-right: 10px;border-radius: 5px;">
            <option value="" style="display: none;">Сортировка</option>
            <option value="ORDER BY `date` DESC">Сначала новые</option>
            <option value="ORDER BY `date` ASC">Сначала старые</option>
            <option value="ORDER BY `completed` ASC">Сначала невыполненные</option>
            <option value="ORDER BY `completed` DESC">Сначала выполненные</option>
        </select>
        <select name="filter" style="margin-right: 10px;border-radius: 5px;">
            <option value="" style="display: none;">Фильтр</option>
            <option value="AND `completed`=false">Только невыполненные</option>
            <option value="AND `completed`=true">Только выполненные</option>
        </select>
        <label for="date" style="margin-right: 10px;">Не позднее: </label>
        <input type="date" name="date" style="margin-right: 10px;border-radius: 5px;">
        <button type="submit" name="accept">Показать</button>
        <button type="reset" name="reset">Сбросить</button>
    </form>
    <ul>
        <?php include 'list_tasks.php'; ?>
    </ul>
</body>
</html>