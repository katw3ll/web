<?php

// Проверяем наличие сессии
if(!session_id()) {
session_start();
}


//Создаем массив со всеми utm-метками 
$arrUtm = [];
if (isset($_SESSION['utm'])) {
	$arrUtm = json_decode($_SESSION['utm']);    
}


// TODO: Отправка на почту



// ===============================

$form_name  = $_POST['form_name'];
$form_url   = $_POST['form_url'];

$title_lead = 'Заявка с сайта '.$_SERVER['SERVER_NAME'] . ' ' . $_POST['form']; //Заголовок лида в amoCRM
$phone = $_POST['user_phone'];
$phone = preg_replace('/\D*/', '', $phone);
$phone = '+'.$phone;
if(isset($_POST['user_name']) && $_POST['user_name'] !== ''){
    $name = $_POST['user_name'];
}else{
    $name = 'Уточнить имя!';
}


// ===============================

if ($phone === '+') die(); // Завершаем скрипт, если не указан телефон


include_once 'amocrm.phar';

try {
    
    // Подключение к amoCRM
    $subdomain = '1musicfamily';                                // Поддомен в амо срм
    $login     = 'shirolapov.production@yandex.ru';                    // Логин в амо срм
    $apikey    = 'be0c9ea3a8e443837168c5da1e1a5f6252f6481c';    // api ключ
    // 8215618c9f5357383f39b85091db89ab8f3f5e0c icanhelpyoufmf@yandex.ru

    $amo = new \AmoCRM\Client($subdomain, $login, $apikey);


    // создаем лида
    $lead = $amo->lead;

    $lead->debug(false); // Режим отладки

    $lead['name'] = $title_lead;
    $lead['responsible_user_id'] = 3474454; // ID ответсвенного 
    $lead['pipeline_id'] = 1015150;         // ID воронки
    $lead['status_id'] = 18620989;          // Статус сделки

    // Добавляем информацию о форме
    $lead->addCustomField(601513, $form_name);
    $lead->addCustomField(602425, $form_url);

    // Добавляем информацию UTM-метках, если они заданы
    if (isset($_SESSION['utm'])) { 
        $lead->addCustomField(601515, $arrUtm->utm_source);
        $lead->addCustomField(601517, $arrUtm->utm_medium);
        $lead->addCustomField(601519, $arrUtm->utm_campaign);
        $lead->addCustomField(601521, $arrUtm->utm_content);
        $lead->addCustomField(601523, $arrUtm->utm_term);
    }
    
    //Ставим направление
    if (isset($_POST['client_select']) && $_POST['client_select'] !== ''){
        $lead->addCustomField(552071, $_POST['client_select']);
    }
    if (isset($_POST['way-course']) && $_POST['way-course'] !== ''){
        $lead->addCustomField(603119, $_POST['way-course']);
    }
    // if (isset($_POST['form_price']) && $_POST['form_price'] !== ''){
    //     $lead['price'] = $_POST['form_price'];
    // }

    // TODO: Дополнительные данные из форм



    //$lead->addCustomField(552071, 1147423);
    

    $lead_id = $lead->apiAdd(); // Отправка лида в CRM



    // Прикрепляем контакную информацию
    $contact = $amo->contact;
    $contact->debug(false);

    
    $contact_search = $amo->contact->apiList(['query' => $phone]);  // Пытаемся найти контакт по номеру

    if(empty($contact_search)){

        $contact['name'] = $name;
        $contact['responsible_user_id'] = 3474454;
        $contact['linked_leads_id'] = $lead_id;

        
        $contact->addCustomField(474912, $phone, 'WORK');

        if (isset($_POST['user_email']) && $_POST['user_email'] !== ''){
            $contact->addCustomField(474914, $_POST['user_email'], 'WORK');
        }
        
        $contact->apiAdd();

    } else{
        $contactid = $contact_search[0]['id'];
        $linked_leads_id = $contact_search[0]['linked_leads_id'];

        array_push($linked_leads_id, $lead_id);
        $contact['linked_leads_id'] = $linked_leads_id;

        $contact->apiUpdate((int)$contactid, 'now');
    }

    if (isset($_POST['user_time']) && $_POST['user_time'] !== ''){
        $note = $amo->note;
    	$note->debug(false); // Режим отладки
    	$note['element_id'] = $lead_id;
    	$note['element_type'] = \AmoCRM\Models\Note::TYPE_LEAD; // 1 - contact, 2 - lead
        $note['note_type'] = \AmoCRM\Models\Note::COMMON; // @see https://developers.amocrm.ru/rest_api/notes_type.php
        $txt = "Желаемое время записи - ".$_POST['user_time']."\n";
    	$note['text'] = $txt;
    	$note->apiAdd();
    }

    // if (isset($_POST['form_price']) && $_POST['form_price'] !== ''){
    //     $note = $amo->note;
    // 	$note->debug(false); // Режим отладки
    // 	$note['element_id'] = $lead_id;
    // 	$note['element_type'] = \AmoCRM\Models\Note::TYPE_LEAD; // 1 - contact, 2 - lead
    //     $note['note_type'] = \AmoCRM\Models\Note::COMMON; // @see https://developers.amocrm.ru/rest_api/notes_type.php
    //     $txt = "Бюджет - ".$_POST['form_price']." руб.\n";
    // 	$note['text'] = $txt;
    // 	$note->apiAdd();
    // }

    if (isset($_POST['text_contacts']) && $_POST['text_contacts'] !== ''){
        $note = $amo->note;
    	$note->debug(false); // Режим отладки
    	$note['element_id'] = $lead_id;
    	$note['element_type'] = \AmoCRM\Models\Note::TYPE_LEAD; // 1 - contact, 2 - lead
        $note['note_type'] = \AmoCRM\Models\Note::COMMON; // @see https://developers.amocrm.ru/rest_api/notes_type.php
        $txt = "Заметка:\n".$_POST['text_contacts']."\n";
    	$note['text'] = $txt;
    	$note->apiAdd();
    }

} catch (\AmoCRM\Exception $e) {
    printf('Error (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
}

