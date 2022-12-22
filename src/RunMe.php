<?php

include "requirements/config.php";
include "requirements/classes.php";
include "requirements/functions.php";

# Webhook qilish.
$sm = $_SERVER;
$host = $sm['SERVER_NAME'];
$botu = str_replace("RunMe.php", "Bot.php", $sm['PHP_SELF']);
$webhook = json_decode(file_get_contents("https://api.telegram.org/bot".TOKEN."/setwebhook?url=https://".$host.$botu."&max_conections=100"));
if($webhook->ok){
    echo "Webhook qilindi!";
}else{
    die("Webhook qilinmadi. Ma'lumotlarni tekshirib qayta urinib ko'ring!");
}

# Baza ma'lumotlarini joylash.
mysqli_query($conn, "CREATE TABLE `adminlar`(`son` int(255) auto_increment primary key, `admin_id` int(20))");
mysqli_query($conn, "CREATE TABLE `step`(`son` int(255) auto_increment primary key, `id` int(30), `step` varchar(20))");
mysqli_query($conn, "CREATE TABLE `azolar`(`son` int(10) auto_increment primary key, `uid` varchar(200), `sana` varchar(20), `soat` varchar(20), `dan` varchar(10), `ga` varchar(10)), `parol` varchar(200)");
mysqli_query($conn, "CREATE TABLE `maxfiy`(`son` int(10) auto_increment primary key, `uid` varchar(200), `soz` varchar(5000), `tarjimasi` varchar(5000))");
mysqli_query($conn, "CREATE TABLE `reklama`(`son` int(10) auto_increment primary key, `forward_id` varchar(100), `uid` varchar(200))");
mysqli_query($conn, "CREATE TABLE `xabar`(`id` int(30) auto_increment primary key, `uid` varchar(50), `type` varchar(50), `forward_id` varchar(50), `caption` varchar(2000), `text` varchar(2000), `file_id` varchar(1000), `status` varchar(50), `message_id` varchar(50), `keyboard` varchar(2000), `yuborildi` varchar(100), `yuborilmadi` varchar(100), `jami` varchar(100), `subject` varchar(100), `clear` varchar(100), `yuborish` varchar(100))");
mysqli_query($conn, "CREATE TABLE `clear-baza`(`son` int(10) auto_increment primary key, `type` varchar(20), `id` varchar(200))");

$bot->sendMessage(["chat_id"=>$admin, "text"=>"Bot ishlash uchun tayyor!"]);

?>