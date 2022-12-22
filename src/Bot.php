<?php
date_default_timezone_set('Asia/Tashkent');
ini_set("log_errors", "off");
include "requirements/config.php";
include "requirements/classes.php";
include "requirements/functions.php";
include "requirements/tgapi.php";
include "requirements/menus.php";

include "actions/for-groups.php";
include "actions/for-private-chats.php";
include "actions/panel.php";
include "actions/datas.php";
include "actions/steps-for-chats.php";

if(isset($update->message)){
	$query = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM `adminlar` WHERE `son`='1'"));
	if(!$query){
		mysqli_query($conn,"INSERT INTO `adminlar` (`son`,`admin_id`) VALUES ('1','$admin')");
	}
}

if((!empty($type)) and ($type == "group" or $type == "supergroup")){
    statistika("group", $chat_id);
	if(!empty($update->message)){
        if(!empty($update->message->chat->username)){
            $username = $update->message->chat->username;
            $glink = "https://t.me/$username";
            $gtitle = base64_encode($title);
            $query = mysqli_query($conn,"SELECT * FROM `guruhlar` WHERE `cid`='$chat_id'");
            if(mysqli_fetch_array($query)){
                mysqli_query($conn,"UPDATE `guruhlar` SET `link`='$glink', `title`='$gtitle' WHERE `cid`='$chat_id'");
            }
            if((mb_stripos($username,"seks") !== false) or (mb_stripos($title,"seks") !== false) or (mb_stripos($username,"gandon") !== false) or (mb_stripos($title,"gandon") !== false)){
                $bot->leaveChat(["chat_id"=>$chat_id]);
                mysqli_query($conn,"DELETE FROM `guruhlar` WHERE `cid`='$chat_id'");
            }
        }else{
            $glink = $bot->getChat(["chat_id"=>$chat_id])['result']['invite_link'];
            $gtitle = base64_encode($title);
            $query = mysqli_query($conn,"SELECT * FROM `guruhlar` WHERE `cid`='$chat_id'");
            if(mysqli_fetch_array($query)){
                mysqli_query($conn,"UPDATE `guruhlar` SET `link`='$glink', `title`='$gtitle' WHERE `cid`='$chat_id'");
            }
        }
        $query = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `guruhlar` WHERE `cid`='$chat_id'"));
		$ason = $bot->getChatMembersCount(["chat_id"=>$chat_id])['result'];
		if($query){
			mysqli_query($conn,"UPDATE `guruhlar` SET `azolar`='$ason' WHERE `cid`='$chat_id'");
        }
    }
}
?>