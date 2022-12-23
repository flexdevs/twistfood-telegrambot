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

$choose_count = json_encode([
    "inline_keyboard"=>[
    [["text"=>"➖","callback_data"=>"minus"],["text"=>"1","callback_data"=>"null"],["text"=>"➕","callback_data"=>"plus"]]
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
    foreach($product as $burgers){
        if($text == $product){
            $bot->sendMessage([
                "chat_id"=>$fid,
                //"photo"=>"https://www.reklamzamani.net/dosyalar/urun/4xNLmo.jpg",
                "text"=>"<b>miqdorini tanlang: $text</b>",
                "parse_mode"=>"html",
                'disable_web_page_preview'=>true,
                "reply_markup"=>$choose_count
                ]);
        }
    }
}




// if(isset($text) and $text != '/start' and $text != '/setlang' and (stripos($text,"/add")!==false)==false){
//     $query = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `maxfiy` WHERE `soz`='$text'"));
//     // $info =  mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `azolar` WHERE `uid`='$fid'"));
//     // if($query){
//     //     $bot->sendMessage([
//     //         "chat_id"=>$fid,
//     //         "text"=>$query['tarjimasi'],
//     //         "parse_mode"=>"html",
//     //         ]);
//     // }else{
//         $info =  mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `azolar` WHERE `uid`='$fid'"));
//         $trans = new TranslateApi();
//         $result = $trans->translate($info['dan'], $info['ga'], $text);
//         $bot->sendMessage([
//                 "chat_id"=>$fid,
//                 "text"=>$result,
//                 "parse_mode"=>"html",
//                 ]);
//     // }
// }

	
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