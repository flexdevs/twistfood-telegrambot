<?php

if((!empty($data)) and (stripos($data,"pazolar|") !== false)){
    ustep($fid);
    $page = explode("|",$data)[1];
    $current = $page * 10;
    $query = mysqli_query($conn,"SELECT * FROM `azolar`");
    $all = mysqli_num_rows($query);
    $bolish = $all/10;
    if(stripos($bolish,".") !== false){
        $cpage = explode(".",$bolish)[0] + 1;
    }else{
        $cpage = $bolish;
    }
    $ppage = $page+1;
    $keyb = [];
    $pback = $page - 1;
    $pnext = $page + 1;
    $matn = '';
    $son1 = $current;
    $query = mysqli_query($conn,"SELECT * FROM `azolar` LIMIT $current, 10");
    while($row = mysqli_fetch_array($query)){
        $son1++;
        $rid = $row['uid'];
        $rsana = $row['sana'];
        $matn .= "$son1) 👤 Foydalanuvchi: <a href='tg://user?id=$rid'>$rid</a>.\n    🗓️ Ro'yhatdan o'tgan sana: $rsana.\n\n";
    }
    if($page == 0 and $cpage > 1){
        array_push($keyb,[["callback_data"=>"null","text"=>"🔴"],["callback_data"=>"hech_nima","text"=>"$ppage/$cpage"],["callback_data"=>"pazolar|$pnext","text"=>"▶️"]]);
    }elseif($page == 0 and $cpage < 2){
        array_push($keyb,[["callback_data"=>"null","text"=>"🔴"],["callback_data"=>"hech_nima","text"=>"$ppage/$cpage"],["callback_data"=>"null","text"=>"🔴"]]);
    }elseif($cpage == ($page+1)){
        array_push($keyb,[["callback_data"=>"pazolar|$pback","text"=>"◀️"],["callback_data"=>"hech_nima","text"=>"$ppage/$cpage"],["callback_data"=>"null","text"=>"🔴"]]);
    }else{
        array_push($keyb,[["callback_data"=>"pazolar|$pback","text"=>"◀️"],["callback_data"=>"hech_nima","text"=>"$ppage/$cpage"],["callback_data"=>"pazolar|$pnext","text"=>"▶️"]]);
    }
    $cplus = $current + 10;
    $bot->editMessageText([
    "chat_id"=>$fid,
    "message_id"=>$mid,
    "text"=>"<b>👤 Botda ro'yhatdan o'tgan foydalanuvchilar ro'yhati.\n🔢 $current-$cplus $all dan:\n\n$matn</b>",
    "parse_mode"=>"html",
    "reply_markup"=>json_encode([
    "inline_keyboard"=>
        $keyb
    ])
    ]);
    exit;
}

if((!empty($data)) and ($data == "bekor")){
    ustep($fid);
    $bot->deleteMessage([
    "chat_id"=>$fid,
    "message_id"=>$mid
    ]);
    $bot->sendMessage([
    "chat_id"=>$fid,
    "text"=>"<b>❌ Bekor qilindi!\n\nXo'sh nima qilamiz?</b>",
    "parse_mode"=>"html",
    "reply_markup"=>getKey("panel"),
    ]);
    exit;
}

if((!empty($data)) and (stripos($data,"delete|") !== false)){
    $query = mysqli_query($conn,"SELECT * FROM `adminlar` WHERE `admin_id`='$fid'");
    $fetch = mysqli_fetch_assoc($query);
    if($fetch['son'] == 1){
        $ex = explode("|",$data);
        $delid = $ex[1];
        $q = mysqli_query($conn,"SELECT `admin_id` FROM `adminlar` WHERE `son`='1'");
        if($delid == mysqli_fetch_assoc($q)['admin_id']){
            $bot->answerCallbackQuery([
            "callback_query_id"=>$call_id,
            "text"=>"📛 O'zingizni adminlikdan olib tashlay olmaysiz!",
            "show_alert"=>true,
            ]);
        }else{
        $query = mysqli_query($conn,"DELETE FROM `adminlar` WHERE `admin_id`='$delid'");
        $bot->deleteMessage([
        "chat_id"=>$fid,
        "message_id"=>$mid
        ]);
        $bot->sendMessage([
        "chat_id"=>$fid,
        "text"=>"<b>✅ Tanlangan admin botda adminlikdan olindi!\n\nXo'sh nima qilamiz?</b>",
        "parse_mode"=>"html",
        "reply_markup"=>getKey("admen")
        ]);
        }
    }else{
        $bot->deleteMessage([
        "chat_id"=>$fid,
        "message_id"=>$mid,
        ]);
        $bot->sendMessage([
        "chat_id"=>$fid,
        "text"=>"<b>❌ Kechirasiz! Kimnidir adminlikdan olib tashlash huquqiga faqat 1-admin ega!</b>",
        "parse_mode"=>"html",
        "reply_markup"=>getKey("admen"),
        ]);
    }
    exit;
}

if((isset($data)) and (stripos($data,"reklamani-") !== false)){
	$action = explode("-",$data)[1];
	if($action == "qoshish"){
		ustep($fid);
		$bot->sendMessage([
		"chat_id"=>$fid,
		"text"=>"<b>✅ Botga qo'ymoqchi bo'lgan reklamangizni qaysidir kanaldan forward qiling:</b>",
		"parse_mode"=>"html",
		"reply_markup"=>getKey("bekor"),
		]);
        $bot->deleteMessage([
            "chat_id"=>$fid,
            "message_id"=>$mid,
        ]);
        step($fid,"add-ad");
	}
	if($action == "korish"){
		ustep($fid);
		$reklama = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM `reklama`"));
		$bot->forwardMessage([
		"chat_id"=>$fid,
		"from_chat_id"=>$reklama['uid'],
		"message_id"=>$reklama['forward_id']
		]);
		$keyboard = [];
		array_push($keyboard,[["text"=>"👁️ Reklamani ko'rish","callback_data"=>"reklamani-korish"]]);
		array_push($keyboard,[["text"=>"❌ Reklamani o'chirish","callback_data"=>"reklamani-ochirish"]]);
		$matn = "<b>📎 Reklama bo'limi:\n\n✅ Xo'sh nima qilamiz?</b>";
		$bot->sendMessage([
		"chat_id"=>$fid,
		"text"=>$matn,
		"parse_mode"=>"html",
		"reply_markup"=>json_encode([
		"inline_keyboard"=>
		$keyboard
		])
		]);
        $bot->deleteMessage([
            "chat_id"=>$fid,
            "message_id"=>$mid,
        ]);
        }
	if($action == "ochirish"){
		ustep($fid);
		$bot->editMessageText([
		"chat_id"=>$fid,
		"message_id"=>$mid,
		"text"=>"✅ <b>Reklama o'chirildi!</b>",
		"parse_mode"=>"html"
		]);
		mysqli_query($conn,"DELETE FROM `reklama`");
    }
    exit;
}

if((!empty($data)) and (stripos($data,"xabarni-korish|") !== false)){
	$sonx = explode("|",$data)[1];
	$marray = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM `xabar` WHERE `id`='$sonx'"));
    $bot->deleteMessage([
        "chat_id"=>$fid,
        "message_id"=>$mid,
    ]);
	if($marray['type'] == "matnli"){
		if($marray['keyboard'] != 'false'){
			$bot->sendMessage([
                "chat_id"=>$fid,
                "text"=>base64_decode($marray['text']),
                "parse_mode"=>"html",
                "reply_markup"=>base64_decode($marray['keyboard']),
			]);
		}else{
            $bot->sendMessage([
                "chat_id"=>$fid,
                "text"=>base64_decode($marray['text']),
                "parse_mode"=>"html"
            ]);
		}
	}
	if($marray['type'] == "forward"){
		$bot->forwardMessage([
            "chat_id"=>$fid,
            "from_chat_id"=>$marray['uid'],
            "message_id"=>$marray['forward_id']
		]);
	}
	if($marray['type'] == "video"){
		if($marray['keyboard'] != 'false'){
			$bot->sendVideo([
                "chat_id"=>$fid,
                "video"=>$marray['file_id'],
                "caption"=>base64_decode($marray['caption']),
                "parse_mode"=>"html",
                "reply_markup"=>base64_decode($marray['keyboard'])
			]);
		}else{
			$bot->sendVideo([
                "chat_id"=>$fid,
                "video"=>$marray['file_id'],
                "caption"=>base64_decode($marray['caption']),
                "parse_mode"=>"html"
			]);
		}
	}
	if($marray['type'] == "audio"){
		if($marray['keyboard'] != 'false'){
			$bot->sendAudio([
                "chat_id"=>$fid,
                "audio"=>$marray['file_id'],
                "caption"=>base64_decode($marray['caption']),
                "parse_mode"=>"html",
                "reply_markup"=>base64_decode($marray['keyboard']),
			]);
		}else{
			$bot->sendAudio([
                "chat_id"=>$fid,
                "audio"=>$marray['file_id'],
                "caption"=>base64_decode($marray['caption']),
                "parse_mode"=>"html"
			]);
		}
	}
	if($marray['type'] == "voice"){
		if($marray['keyboard'] != 'false'){
			$bot->sendVoice([
                "chat_id"=>$fid,
                "voice"=>$marray['file_id'],
                "caption"=>base64_decode($marray['caption']),
                "parse_mode"=>"html",
                "reply_markup"=>base64_decode($marray['keyboard'])
			]);
		}else{
			$bot->sendVoice([
                "chat_id"=>$fid,
                "voice"=>$marray['file_id'],
                "caption"=>base64_decode($marray['caption']),
                "parse_mode"=>"html"
			]);
		}
	}
	if($marray['type'] == "photo"){
		if($marray['keyboard'] != 'false'){
			$bot->sendPhoto([
                "chat_id"=>$fid,
                "photo"=>$marray['file_id'],
                "caption"=>base64_decode($marray['caption']),
                "parse_mode"=>"html",
                "reply_markup"=>base64_decode($marray['keyboard']),
			]);
		}else{
			$bot->sendPhoto([
                "chat_id"=>$fid,
                "photo"=>$marray['file_id'],
                "caption"=>base64_decode($marray['caption']),
                "parse_mode"=>"html"
			]);
		}
	}
	$bot->sendMessage([
        "chat_id"=>$fid,
        "text"=>"<b>✅ Xo'sh nima qilamiz?</b>",
        "parse_mode"=>"html",
        "reply_markup"=>json_encode([
        "inline_keyboard"=>[
        [["text"=>"👁️ Xabarni ko'rish","callback_data"=>"xabarni-korish|$sonx"]],
        [["text"=>"📨 Yuborishni boshlash","callback_data"=>"xabarni-yuborish|$sonx"]],
        [["text"=>"❌ Xabarni bekor qilish","callback_data"=>"xabarni-bekor|$sonx"]]
        ]
        ])
    ]);
    exit;
}

if((!empty($data)) and (stripos($data,"xabarni-bekor|") !== false)){
	$sonx = explode("|",$data)[1];
	$bot->deleteMessage([
        "chat_id"=>$fid,
        "message_id"=>$mid,
    ]);
	$bot->sendMessage([
	"chat_id"=>$fid,
	"text"=>"<b>❌ Xabar yuborish bekor qilindi!</b>",
	"parse_mode"=>"html",
	"reply_markup"=>getKey("panel"),
	]);
	mysqli_query($conn,"DELETE FROM `xabar` WHERE `id`='$sonx'");
    unlink("xabar/$fid.send");
    exit;
}

if((isset($data)) and (stripos($data,"xabarni-yuborish|") !== false)){
	$sonx = explode("|",$data)[1];
	$bot->deleteMessage([
        "chat_id"=>$fid,
        "message_id"=>$mid,
    ]);
	$send_id = $bot->sendMessage([
        "chat_id"=>$fid,
        "text"=>"<b>✅ Xabar yuborish boshlanmoqda! Xo'sh nima qilamiz?</b>",
        "parse_mode"=>"html"
	])['result']['message_id'];
	mysqli_query($conn,"UPDATE `xabar` SET `status`='yuborilmoqda', `message_id`='$send_id' WHERE `id`='$sonx'");
	unlink("xabar/$fid.send");
	$bot->sendMessage([
        "chat_id"=>$fid,
        "text"=>"<b>Xo'sh nima qilamiz?</b>",
        "parse_mode"=>"html",
        "reply_markup"=>getKey("panel"),
    ]);
    exit;
}

?>