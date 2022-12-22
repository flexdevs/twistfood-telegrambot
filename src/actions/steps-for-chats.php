<?php

if((isset($update->message)) and (!empty(mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `adminlar` WHERE `admin_id`=$fid"))))){

    if((!empty($text)) and (getstep($fid) == "addadmin")){
        if($text == "/panel" or $text == "/start" or $text == "👮🏻‍♂️ Bot adminlari" or $text == "➕ Admin qo'shish" or $text == "➖ Adminlikdan olish"){
        return false;
        }else{
            $query = mysqli_query($conn,"SELECT * FROM `adminlar` WHERE `admin_id`='$fid'");
            $fetch = mysqli_fetch_assoc($query);
            if($fetch['son'] == 1){
                $q = mysqli_query($conn,"SELECT `admin_id` FROM `adminlar` WHERE `admin_id`='$text'");
                if(!mysqli_fetch_array($q)){
                    mysqli_query($conn,"INSERT INTO `adminlar` (`admin_id`) VALUES ('$text')");
                    $bot->sendMessage([
                    "chat_id"=>$fid,
                    "text"=>"<b>✅ Admin muvaffaqtiyatli qo'shildi!</b>",
                    "parse_mode"=>"html",
                    "reply_markup"=>getKey("admen"),
                    ]);
                    ustep($fid);
                }else{
                    $bot->sendMessage([
                    "chat_id"=>$fid,
                    "text"=>"<b>❌ Kechirasiz, botda bunday admin avvaldan mavjud!</b>",
                    "parse_mode"=>"html",
                    "reply_markup"=>getKey("bekor"),
                    ]);
                }
            }else{
                ustep($fid);
                $bot->sendMessage([
                "chat_id"=>$fid,
                "text"=>"<b>❌ Kechirasiz! Kimnidir admin qilish huquqiga faqat 1-admin ega!</b>",
                "parse_mode"=>"html",
                "reply_markup"=>getKey("admen"),
                ]);
            }
        }
        exit;
    }

    
    if((!empty($update->message)) and (getstep($fid) == "add-ad")){
        if($update->message->forward_from_chat){
            ustep($fid);
            $bot->sendMessage([
            "chat_id"=>$fid,
            "text"=>"<b>✅ Reklama qo'shildi!</b>",
            "parse_mode"=>"html",
            "reply_markup"=>getKey("panel"),
            ]);
            mysqli_query($conn,"INSERT INTO `reklama` (`forward_id`,`uid`) VALUES ('$mid','$fid')");
        }else{
            $bot->sendMessage([
            "chat_id"=>$fid,
            "text"=>"<b>❗ Botga qo'ymoqchi bo'lgan reklamangizni qaysidir kanaldan forward qiling:</b>",
            "parse_mode"=>"html"
            ]);
        }
        exit;
    }

    if((!empty($update->message)) and (getstep($fid) == "userlarga-xabar")){
        if(($text) and (!$update->message->forward_from_chat)){
            ustep($fid);
            $bot->sendMessage([
            "chat_id"=>$fid,
            "text"=>"<b>✅ Xabaringiz qabul qilindi! Shaffof tugma qo'shishni xohlaysizmi? Bu tarzda tugma qo'shing:\n\n[Yangiliklar-https://t.me/link][Yangiliklar-https://t.me/link]\n[Yangiliklar-https://t.me/link]</b>",
            "parse_mode"=>"html",
            "reply_markup"=>json_encode([
            "resize_keyboard"=>true,
            "one_time_keyboard"=>true,
            "keyboard"=>[
            [["text"=>"❌ Kerak emas"]],
            [["text"=>"🔙 Ortga"]],
            ]
            ]),
            ]);
            $raqam = file_get_contents("xabar/$fid.send");
            $matn = base64_encode($text);
            mysqli_query($conn,"INSERT INTO `xabar` (`id`,`uid`,`type`,`text`,`status`,`yuborildi`,`yuborilmadi`,`jami`,`subject`) VALUES ('$raqam','$fid','matnli','$matn','tayyor-emas','0','0','0','to-users')");
            step($fid,"keyboard");
        }
        if(($update->message->video) and (!$update->message->forward_from_chat)){
            ustep($fid);
            $bot->sendMessage([
            "chat_id"=>$fid,
            "text"=>"<b>✅ Video qabul qilindi! Endi esa video uchun sharh kiriting:</b>",
            "parse_mode"=>"html",
            "reply_markup"=>getKey("bekor"),
            ]);
            $fileid = $update->message->video->file_id;
            $raqam = file_get_contents("xabar/$fid.send");
            mysqli_query($conn,"INSERT INTO `xabar` (`id`,`uid`,`type`,`file_id`,`status`,`yuborildi`,`yuborilmadi`,`jami`,`subject`) VALUES ('$raqam','$fid','video','$fileid','tayyor-emas','0','0','0','to-users')");
            step($fid,"caption");
        }
        if(($update->message->audio) and (!$update->message->forward_from_chat)){
            ustep($fid);
            $bot->sendMessage([
            "chat_id"=>$fid,
            "text"=>"<b>✅ Audio qabul qilindi! Endi esa audio uchun sharh kiriting:</b>",
            "parse_mode"=>"html",
            "reply_markup"=>getKey("bekor"),
            ]);
            $fileid = $update->message->audio->file_id;
            $raqam = file_get_contents("xabar/$fid.send");
            mysqli_query($conn,"INSERT INTO `xabar` (`id`,`uid`,`type`,`file_id`,`status`,`yuborildi`,`yuborilmadi`,`jami`,`subject`) VALUES ('$raqam','$fid','audio','$fileid','tayyor-emas','0','0','0','to-users')");
            step($fid,"caption");
        }
        if(($update->message->photo) and (!$update->message->forward_from_chat)){
            ustep($fid);
            $bot->sendMessage([
            "chat_id"=>$fid,
            "text"=>"<b>✅ Rasm qabul qilindi! Endi esa rasm uchun sharh kiriting:</b>",
            "parse_mode"=>"html",
            "reply_markup"=>getKey("bekor"),
            ]);
            $fileid = $update->message->photo[0]->file_id;
            $raqam = file_get_contents("xabar/$fid.send");
            mysqli_query($conn,"INSERT INTO `xabar` (`id`,`uid`,`type`,`file_id`,`status`,`yuborildi`,`yuborilmadi`,`jami`,`subject`) VALUES ('$raqam','$fid','photo','$fileid','tayyor-emas','0','0','0','to-users')");
            step($fid,"caption");
        }
        if(($update->message->voice) and (!$update->message->forward_from_chat)){
            ustep($fid);
            $bot->sendMessage([
            "chat_id"=>$fid,
            "text"=>"<b>✅ Ovozli xabar qabul qilindi! Endi esa ovozli xabar uchun sharh kiriting:</b>",
            "parse_mode"=>"html",
            "reply_markup"=>getKey("bekor"),
            ]);
            $fileid = $update->message->voice->file_id;
            $raqam = file_get_contents("xabar/$fid.send");
            mysqli_query($conn,"INSERT INTO `xabar` (`id`,`uid`,`type`,`file_id`,`status`,`yuborildi`,`yuborilmadi`,`jami`,`subject`) VALUES ('$raqam','$fid','voice','$fileid','tayyor-emas','0','0','0','to-users')");
            step($fid,"caption");
        }
        if($update->message->forward_from_chat){
            ustep($fid);
            $raqam = file_get_contents("xabar/$fid.send");
            $bot->sendMessage([
            "chat_id"=>$fid,
            "text"=>"<b>✅ Forward xabar qabul qilindi! Xo'sh nima qilamiz?</b>",
            "parse_mode"=>"html",
            "reply_markup"=>json_encode([
            "inline_keyboard"=>[
            [["text"=>"👁️ Xabarni ko'rish","callback_data"=>"xabarni-korish|$raqam"]],
            [["text"=>"📨 Yuborishni boshlash","callback_data"=>"xabarni-yuborish|$raqam"]],
            [["text"=>"❌ Xabarni bekor qilish","callback_data"=>"xabarni-bekor|$raqam"]],
            ]
            ]),
            ]);
            mysqli_query($conn,"INSERT INTO `xabar` (`id`,`uid`,`type`,`forward_id`,`status`,`yuborildi`,`yuborilmadi`,`jami`,`keyboard`,`subject`) VALUES ('$raqam','$fid','forward','$mid','tayyor-emas','0','0','0','false','to-users')");
        }
        exit;
    }

    if((!empty($text)) and (getstep($fid) == "caption")){
        ustep($fid);
        $bot->sendMessage([
        "chat_id"=>$fid,
        "text"=>"<b>✅ Sharhingiz qabul qilindi! Shaffof tugma qo'shishni xohlaysizmi?  Bu tarzda tugma qo'shing:\n\n[Yangiliklar-https://t.me/link][Yangiliklar-https://t.me/link]\n[Yangiliklar-https://t.me/link]</b>",
        "parse_mode"=>"html",
        "reply_markup"=>json_encode([
        "resize_keyboard"=>true,
        "one_time_keyboard"=>true,
        "keyboard"=>[
        [["text"=>"❌ Kerak emas"]],
        [["text"=>"🔙 Ortga"]],
        ]
        ]),
        ]);
        step($fid,"keyboard");
        $sonx = file_get_contents("xabar/$fid.send");
        $sharh = base64_encode($text);
        mysqli_query($conn,"UPDATE `xabar` SET `caption`='$sharh' WHERE `id`='$sonx'");
        exit;
    }

    if((!empty($text)) and (getstep($fid) == "keyboard")){
        ustep($fid);
        if($text == "❌ Kerak emas"){
            $currentp = file_get_contents("xabar/$fid.send");
            $bot->sendMessage([
            "chat_id"=>$fid,
            "text"=>"<b>✅ Tugma bekor qilindi! Xo'sh nima qilamiz?</b>",
            "parse_mode"=>"html",
            "reply_markup"=>json_encode([
            "inline_keyboard"=>[
            [["text"=>"👁️ Xabarni ko'rish","callback_data"=>"xabarni-korish|$currentp"]],
            [["text"=>"📨 Yuborishni boshlash","callback_data"=>"xabarni-yuborish|$currentp"]],
            [["text"=>"❌ Xabarni bekor qilish","callback_data"=>"xabarni-bekor|$currentp"]],
            ]
            ]),
            ]);
            mysqli_query($conn,"UPDATE `xabar` SET `keyboard`='false' WHERE `id`='$currentp'");
        }else{
            $key = [];
            $keyboard = [];
            $qator = explode("\n",$text);
            foreach($qator as $row){
                preg_match_all("|\[(.*)\]|U",$row,$k);
                foreach($k[1] as $k2){
                $nak = explode("-",$k2);
                array_push($key,["url"=>"$nak[1]", "text"=>"$nak[0]"]);
                }
                $keyboard[] = $key;
                $key = [];
            }
            $tugmalar = base64_encode(json_encode([
            "inline_keyboard"=>
                $keyboard,
            ]));
            $currentp = file_get_contents("xabar/$fid.send");
            $bot->sendMessage([
            "chat_id"=>$fid,
            "text"=>"<b>✅ Tugmalar qabul qilindi! Xo'sh nima qilamiz?</b>",
            "parse_mode"=>"html",
            "reply_markup"=>json_encode([
            "inline_keyboard"=>[
            [["text"=>"👁️ Xabarni ko'rish","callback_data"=>"xabarni-korish|$currentp"]],
            [["text"=>"📨 Yuborishni boshlash","callback_data"=>"xabarni-yuborish|$currentp"]],
            [["text"=>"❌ Xabarni bekor qilish","callback_data"=>"xabarni-bekor|$currentp"]],
            ]
            ]),
            ]);
            mysqli_query($conn,"UPDATE `xabar` SET `keyboard`='$tugmalar' WHERE `id`='$currentp'");
        }
        exit;
    }

}

?>