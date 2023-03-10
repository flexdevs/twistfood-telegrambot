<?php

function createmenu($array, $count){
    array_unshift($array, '⬅️ Orqaga', '📥 Savat');
	$categories=array_chunk($array, $count);
    $categorymenu = json_encode([
        "resize_keyboard"=>true,
        "one_time_keyboard"=>true,
        "keyboard"=>$categories
    ]);
    return $categorymenu;
}

function createproductmenu($array, $count){
    array_unshift($array, '⬅️ Orqaga', '📥 Savat');
	$categories=array_chunk($array, $count);
    $categorymenu = json_encode([
        "resize_keyboard"=>true,
        "one_time_keyboard"=>true,
        "keyboard"=>$categories
    ]);
    return $categorymenu;
}

$users = array('1683821585', '1683821525', '6683821585', '7773821585');

$phones = array('+998942732650', '+998977778899');

$category_menu = array('🍔Burger', '🥪Clab - Sendvich', '🌯Lavash', '🥤Ichimliklar', '🌮Shauarma');

$burgers = array('Gamburger', 'Chizburger', 'Dabl Burger', 'Dabl Chiz');

$lavash = array("Lavash mol go'shtli", "Lavash mol go'shtli");

$nomi = '';

$savatcha = [];

$narxi = 15000;

$choose_count = json_encode([
    "resize_keyboard"=>true,
    "one_time_keyboard"=>true,
    "keyboard"=>[
        [["text"=>"1"]],
        [["text"=>"2"],["text"=>"3"],["text"=>"4"]],
        [["text"=>"5"],["text"=>"6"],["text"=>"7"]],
        [["text"=>"8"],["text"=>"9"],["text"=>"10"]],
        [["text"=>"⬅️ Orqaga"]]
    ]
    ]);

$userstep = file_get_contents('../step.txt');

if((!empty($type)) and ($type == "private")){

$mainmenu = json_encode([
    "resize_keyboard"=>true,
    "one_time_keyboard"=>true,
    "keyboard"=>[
        [["text"=>"🛍 Buyurtma berish"]],
        [["text"=>"🎉 Aksiya"], ["text"=>"📋 Mening buyurtmalarim"]],
        [["text"=>"🏘 Barcha filiallar"]], // ["text"=>"⚙️ Sozlamalar"]
    ]
]);

if($text == "📥 Savat"){
    $bot->sendMessage([
        "chat_id"=>$fid,
        "text"=>$savatcha[0],
        "parse_mode"=>"html",
        'disable_web_page_preview'=>true,
        ]);
}


if((!empty($text)) and ($text == "/start")){
    $bot->sendMessage([
    "chat_id"=>$fid,
    "text"=>"<b>Buyurtma berishni boshlash uchun 🛍 Buyurtma berish tugmasini bosing
     
Shuningdek, aksiyalarni ko'rishingiz va bizning filiallar bilan tanishishingiz mumkin
    
👉 <a href='https://telegra.ph/Menyu-12-19-39'>TwistFood menu</a></b>",
    "parse_mode"=>"html",
    'disable_web_page_preview'=>true,
    "reply_markup"=>$mainmenu
    ]);
}

if((!empty($text)) and ($text == "🛍 Buyurtma berish")){
    if($fid == $users[0]){
        file_put_contents('../step.txt', "enter_phone"); 
        $bot->sendMessage([
            "chat_id"=>$fid,
            "text"=>"<b>Buyurtma berish uchun botdan ro'yhatdan o'ting! 

📞 Ro'yxatdan o'tish uchun telefon raqamingizni kiriting. 
            
Raqamni +998***** shaklida yuboring.</b>",
            "parse_mode"=>"html",
            'disable_web_page_preview'=>true,
            "reply_markup"=>json_encode([
                "resize_keyboard"=>true,
                "one_time_keyboard"=>true,
                "keyboard"=>[
                    [["text"=>"📞 Telefon raqamni yuborish","request_contact"=>true]]
            ]
            ]),
            ]);
    }else{
        file_put_contents('../step.txt', "enter_location"); 
        $bot->sendMessage([
            "chat_id"=>$fid,
            "text"=>"<b>Buyurtma berishni davom ettirish uchun lakatsiyangizni yuboring!</b>",
            "parse_mode"=>"html",
            'disable_web_page_preview'=>true,
            "reply_markup"=>json_encode([
                "resize_keyboard"=>true,
                "one_time_keyboard"=>true,
                "keyboard"=>[
                    [["text"=>"📍 Lakatsiya yuborish","request_location"=>true]]
            ]
            ]),
            ]);
    }
        
}

if(isset($phone_number) && $phone_number == $phones[0])
{
    if($userstep == 'enter_phone'){
        file_put_contents('../step.txt', "enter_sms"); 
        $bot->sendMessage([
            "chat_id"=>$fid,
            "text"=>"<b>💬 | Telefon raqamga tasdiqlash kodi yuborildi. Iltimos, kodni kiriting.</b>",
            "parse_mode"=>"html",
            ]);
    }
}

if(isset($location)){
    if($userstep == 'enter_location'){
        $bot->sendMessage([
            "chat_id"=>$fid,
            "text"=>"<b>Manzilingiz:\n$latitude\n$longitude\nShu manzil to'grimi?</b>",
            "parse_mode"=>"html",
            'disable_web_page_preview'=>true,
            "reply_markup"=>json_encode([
                "resize_keyboard"=>true,
                "one_time_keyboard"=>true,
                "keyboard"=>[
                    [["text"=>"Ha ✅"]],
                    [["text"=>"Yo'q, Qaytadan yuborish","request_location"=>true]]
            ]
            ]),
            ]);
    }
}

if($text == 'Ha ✅' && $userstep == 'enter_location'){
    file_put_contents('../step.txt', "choose_category");
    $bot->sendMessage([
        "chat_id"=>$fid,
        "text"=>"<b>Kategoriyani tanlang</b>",
        "parse_mode"=>"html",
        'disable_web_page_preview'=>true,
        "reply_markup"=>createmenu($category_menu, 2)
        ]);
}

if($text == 1245 && $userstep == 'enter_sms'){
    file_put_contents('../step.txt', "enter_fish"); 
    $bot->sendMessage([
        "chat_id"=>$fid,
        "text"=>"<b>Iltimos F.I.SH kiriting.\n\nMasalan: A'zamjon Shaydullayev Uugbekivich shaklida yuboring</b>",
        "parse_mode"=>"html",
        ]);
}

if(isset($text) && $userstep == 'enter_fish'){
    file_put_contents('../step.txt', "choose_category");
    $bot->sendMessage([
        "chat_id"=>$fid,
        "text"=>"<b>Kategoriyani tanlang</b>",
        "parse_mode"=>"html",
        'disable_web_page_preview'=>true,
        "reply_markup"=>createmenu($category_menu, 2)
        ]);
        // unlink("../step.txt");
}

if(isset($text) && $userstep == 'choose_category'){
    foreach($category_menu as $category){
        if($text == $category){
            file_put_contents('../step.txt', "choose_product");
            $bot->sendMessage([
                "chat_id"=>$fid,
                "text"=>"<b>Mahsulotni tanlang: $text</b>",
                "parse_mode"=>"html",
                'disable_web_page_preview'=>true,
                "reply_markup"=>createproductmenu($burgers, 2)
                ]);
        }
    }
}

if(isset($text) && $userstep == 'choose_product'){
    foreach($burgers as $product){
        if($text == $product){
            $nomi = $text;
            file_put_contents('../step.txt', "choose_count");
            $bot->sendPhoto([
                "chat_id"=>$fid,
                "photo"=>"https://www.reklamzamani.net/dosyalar/urun/4xNLmo.jpg",
                "caption"=>"<b>Nomi: $nomi\nMa'lumot: \nNarxi:$narxi \n\nBuyurtmani davom ettirish uchun miqdorini tanlang</b>",
                "parse_mode"=>"html",
                'disable_web_page_preview'=>true,
                "reply_markup"=>$choose_count
                ]);
        }
    }
}

if(isset($text) && $userstep == 'choose_count'){
    $num = (int)$text;
    if($num >= 1 && $num <= 10){
        file_put_contents('../step.txt', "add_basket");
        $bot->sendPhoto([
            "chat_id"=>$fid,
            "photo"=>"https://www.reklamzamani.net/dosyalar/urun/4xNLmo.jpg",
            "caption"=>"<b>Nomi: $nomi\nMa'lumot: \nUmumiy narxi:".$narxi*$num." \nSavatga qo'shilsinmi?</b>",
            "parse_mode"=>"html",
            'disable_web_page_preview'=>true,
            "reply_markup"=>json_encode([
                "resize_keyboard"=>true,
                "one_time_keyboard"=>true,
                "keyboard"=>[
                    [["text"=>"Ha ✅"]],
                    [["text"=>"Yo'q ❌"]],
            ]
            ]),
            ]);
    }
}

if(isset($text) && $userstep == 'add_basket'){
    if($text == "Ha ✅"){
        file_put_contents('../step.txt', "choose_category");
        array_push($savatcha, "salom");
        $bot->sendMessage([
            "chat_id"=>$fid,
            "text"=>"<b>Savatchaga qo'shildi ✅</b>",
            "parse_mode"=>"html",
            'disable_web_page_preview'=>true,
            "reply_markup"=>createmenu($category_menu, 2)
            ]);
    }elseif($text == "Yo'q ❌"){
        file_put_contents('../step.txt', "choose_category");
        $bot->sendMessage([
            "chat_id"=>$fid,
            "text"=>"<b>Savatchaga qo'shilmadi ❌</b>",
            "parse_mode"=>"html",
            'disable_web_page_preview'=>true,
            "reply_markup"=>createmenu($category_menu, 2)
            ]);
    }
    
}

if(isset($text) && $text == "⬅️ Orqaga"){
    if($userstep == 'choose_category'){
        $bot->sendMessage([
            "chat_id"=>$fid,
            "text"=>"<b>Buyurtma berishni boshlash uchun 🛍 Buyurtma berish tugmasini bosing
     
Shuningdek, aksiyalarni ko'rishingiz va bizning filiallar bilan tanishishingiz mumkin
                
👉 <a href='https://telegra.ph/Menyu-12-19-39'>TwistFood menu</a></b>",
            "parse_mode"=>"html",
            'disable_web_page_preview'=>true,
            "reply_markup"=>$mainmenu
            ]);
    }elseif($userstep == 'choose_product' || $userstep == 'choose_count'){
        file_put_contents('../step.txt', "choose_category");
        $bot->sendMessage([
            "chat_id"=>$fid,
            "text"=>"<b>Kategoriyani tanlang</b>",
            "parse_mode"=>"html",
            'disable_web_page_preview'=>true,
            "reply_markup"=>createmenu($category_menu, 2)
            ]);
    }
}

if((!empty($text)) and ($text == "/speed")){
    $start_time = round(microtime(true) * 1000);
    $send = $bot->sendMessage([
    'chat_id'=>$fid,
    'text'=>"🚀 Ping: 0 ms.",
    ])['result']['message_id'];
    $end_time = round(microtime(true) * 1000);
    $time_taken = $end_time - $start_time;
    $bot->editMessageText([
    "chat_id"=>$fid,
    "message_id"=>$send,
    "text"=>"🚀 Ping: $time_taken ms.",
    ]);
    exit;
}



if((!empty($text)) and ($text == "📊 Statistika")){
    $soat = date("H");
    $sana = date("d-m-Y");
    $us = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `azolar`"));
    $gra = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `guruhlar`"));
    $grd = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `delguruh`"));
    $ubugun = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `azolar` WHERE `sana`='$sana'"));
    $soats = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `azolar` WHERE `sana`='$sana' AND `soat`='$soat'"));
    $gbugun = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `guruhlar` WHERE `sana`='$sana'"));
    $ybugun = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `delguruh` WHERE `sana`='$sana'"));
    $soatg = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `guruhlar` WHERE `sana`='$sana' AND `soat`='$soat'"));
    $gr = $gra + $grd;
    $count = $us + $gr;
    $soat1 = date("H:i:s");
    $sana1 = date("d.m.Y");
    $bot->sendMessage([
    'chat_id'=>$admin,
    'text'=>"📊 Bot statistikasi\n\nBarcha foydalanuvchilar: <b>$us</b> ta\n1 soat ichida qo'shilgan foydalanuvchilar: $soats ta\nBugun qo'shilgan foydalanuvchilar: $ubugun\n\nBugun: $sana1\nSoat: $soat1",
    'parse_mode'=>"html",
    ]);
    statistika("user", $fid);
    exit;
}

}
?>