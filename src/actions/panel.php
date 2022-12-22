<?php

if((!empty($update->message)) and (!empty(mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `adminlar` WHERE `admin_id`=$fid"))))){

    if((!empty($text)) and ($text == "/panel")){
        ustep($fid);
        $bot->sendMessage([
        "chat_id"=>$fid,
        "text"=>"<b>Salom hurmatli admin! Panelga xush kelibsiz! Xo'sh nima qilamiz?</b>",
        "parse_mode"=>"html",
        "reply_markup"=>getKey("panel"),
        ]);
        exit;
    }

    if((!empty($text)) and ($text == "🔙 Ortga")){
        ustep($fid);
        $bot->sendMessage([
        "chat_id"=>$fid,
        "text"=>"<b>Salom hurmatli admin! Panelga xush kelibsiz! Xo'sh nima qilamiz?</b>",
        "parse_mode"=>"html",
        "reply_markup"=>getKey("panel"),
        ]);
        exit;
    }

    if((!empty($text)) and ($text == "👮🏻‍♂️ Admin menyusi")){
        ustep($fid);
        $bot->sendMessage([
        "chat_id"=>$fid,
        "text"=>"<b>👮🏻‍♂️ Admin menyusi\n\nXo'sh nima qilamiz?</b>",
        "parse_mode"=>"html",
        "reply_markup"=>getKey("admen"),
        ]);
        exit;
    }

    if((!empty($text)) and ($text == "👮🏻‍♂️ Bot adminlari")){
        ustep($fid);
        $query = mysqli_query($conn,"SELECT `admin_id` FROM `adminlar`");
        $son = 0;
        $admins = '';
        while($id = mysqli_fetch_array($query)){
            $admin_id = $id['admin_id'];
            $getname = $bot->getChat([
            "chat_id"=>$admin_id,
            ])['result']['first_name'];
            if($getname){
                $gname = $getname;
            }else{
                $gname = "Foydalanuvchi";
            }
            $son+=1;
            $admins .= "$son) <a href='tg://user?id=$admin_id'>$gname</a>\n";
        }
        $bot->sendMessage([
        "chat_id"=>$fid,
        "text"=>"<b>$text\n\n$admins</b>",
        "parse_mode"=>"html"
        ]);
        exit;
    }

    if((!empty($text)) and ($text == "➖ Adminlikdan olish")){
        ustep($fid);
        $keyboard = [];
        $query = mysqli_query($conn,"SELECT `admin_id` FROM `adminlar`");
        while($row = mysqli_fetch_assoc($query)){
            $id = $row['admin_id'];
            $getname = $bot->getChat([
            "chat_id"=>$id,
            ])['result']['first_name'];
            if($getname){
                $gname = $getname;
            }else{
                $gname = "Foydalanuvchi";
            }
            array_push($keyboard,[["callback_data"=>"delete|$id", "text"=>"$gname"]]);
        }
        array_push($keyboard,[["callback_data"=>"bekor", "text"=>"🚫 Bekor qilish"]]);
        $bot->sendMessage([
        "chat_id"=>$fid,
        "text"=>"<b>Aynan kimni adminlikdan olmoqchisiz? Tanlang:</b>",
        "parse_mode"=>"html",
        "reply_markup"=>json_encode([
        "inline_keyboard"=>
        $keyboard,
        ])
        ]);
        exit;
    }

    if((!empty($text)) and ($text == "➕ Admin qo'shish")){
        step($fid,"addadmin");
        $bot->sendMessage([
        "chat_id"=>$fid,
        "text"=>"<b>📝 Botga kimni admin qilmoqchisiz?\n\nTayinlamoqchi bo'lgan odamning ID raqamini kiriting:</b>",
        "parse_mode"=>"html",
        "reply_markup"=>getKey("bekor"),
        ]);
        exit;
    }

   
    if((!empty($text)) and ($text == "📎 Reklama bo'limi")){
        ustep($fid);
        if(mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM `adminlar` WHERE `admin_id`='$fid'"))['son'] == 1){
            $reklama = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM `reklama`"));
            $keyboard = [];
            if($reklama){
                array_push($keyboard,[["text"=>"👁️ Reklamani ko'rish","callback_data"=>"reklamani-korish"]]);
                array_push($keyboard,[["text"=>"❌ Reklamani o'chirish","callback_data"=>"reklamani-ochirish"]]);
                $matn = "<b>📎 Reklama bo'limi:\n\n✅ Hozirda reklama ulangan!</b>";
            }else{
                array_push($keyboard,[["text"=>"➕ Reklama qo'shish","callback_data"=>"reklamani-qoshish"]]);
                $matn = "<b>📎 Reklama bo'limi:\n\n❌ Hozirda reklama ulanmagan!</b>";
            }
            $bot->sendMessage([
            "chat_id"=>$fid,
            "text"=>$matn,
            "parse_mode"=>"html",
            "reply_markup"=>json_encode([
            "inline_keyboard"=>
            $keyboard
            ])
            ]);
        }else{
            $bot->sendMessage([
            "chat_id"=>$fid,
            "text"=>"📛 Reklama bo'limiga kirishga sizning huquqingiz yo'q!",
            "parse_mode"=>"html",
            "reply_markup"=>getKey("panel"),
            ]);
        }
        exit;
    }


    if((!empty($text)) and ($text == "📨 Xabar yuborish")){
        $check = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM `adminlar` WHERE `admin_id`='$fid'"))['son'];
        if($check == 1){
            $send = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM `xabar` WHERE `status`='yuborilmoqda'"));
            if($send){
                ustep($fid);
                $bot->sendMessage([
                "chat_id"=>$fid,
                "reply_to_message_id"=>$send['message_id'],
                "text"=>"<b>❌ Afsuski buning iloji yo'q! Chunki hozirda xabar yuborish ishlari olib borilmoqda.</b>",
                "parse_mode"=>"html",
                "reply_markup"=>getKey("panel"),
                ]);
            }else{
                ustep($fid);
                $bot->sendMessage([
                "chat_id"=>$fid,
                "text"=>"<b>✅ Yubormoqchi bo'lgan narsangizni botga yuboring!</b>",
                "parse_mode"=>"html",
                "reply_markup"=>getKey("bekor"),
                ]);
                step($fid,"userlarga-xabar");
                $son = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `xabar`")) + 1;
                if(!is_dir("xabar")){
                    mkdir("xabar");
                }
                file_put_contents("xabar/$fid.send",$son);
            }
        }else{
            ustep($fid);
            $bot->sendMessage([
            "chat_id"=>$fid,
            "text"=>"<b>❗ Kechirasiz sizning xabar yuborishga huquqingiz yo'q!</b>",
            "parse_mode"=>"html",
            "reply_markup"=>getKey("panel"),
            ]);
        }
        exit;
    }

}

?>