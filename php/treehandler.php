<?php

include 'TreeClass.php';

//Проверям пришёл ли запрос от ajax
if ($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
    return;
}


//Задаем параметры подключения к базе данных. Эти параметры передаются в класс Tree через глобальный массив
$host = "127.0.0.1";
$user = "root";
$pass = "";
$dbName = "jqtree_db";


$query = '';

//Инициализируем объект Tree отвечающий за обработку данных дерева 
$tree = new Tree();

//Проверяем какое действие пришло в запросе - сохранить или получить данные

if ($_GET['savetree']) {
    $tree->SetQueryToDB(json_decode($_GET['savetree'], true), $query);
    $tree->SaveTreeData($query);
}

if ($_GET['gettree'] == 'true') {
    $tree->GetTreeData();
}




