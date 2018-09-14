<?php
header('Content-type: application/json');
require_once('class/zohoCrm/Api.php');

$newpost = array_map('htmlspecialchars', $_POST);
$api = new zohoCrm\Api();
$existLead = $api->getLeadByPhone($newpost["Phone"]);

if(!empty($existLead)) {

	$leadJson = json_decode($existLead, true);
	$leadid = $leadJson["data"][0]["id"];
    if (in_array('error', $leadJson)) {      
        echo json_encode(
    		array(
    			"message" => "Возникла ошибка: '{$leadJson['message']}'. Попробуйте позже."
    		)
        );

    } elseif(!empty($leadid)) {
    	$newDeal = $api->addDeal("{$leadid}");
    	$newDeal = json_decode($newDeal, true);
		if (array_key_exists('data', $newDeal)) {
	    	echo json_encode(
        		array(
        			"message" => "Лид с таким телефоном уже есть. Сделка добавлена"
        		)
        	);  
	    }
    } 
} else {
	$newLead = $api->addLead($newpost);
	$newLead = json_decode($newLead, true);
	if (array_key_exists('data', $newLead)) {
    	echo json_encode(
        	array(
        		"message" => "Лид добавлен"
        	)
        );     
    }
}
