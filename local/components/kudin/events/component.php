<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$log = new CrmLog();

$arParams['ID'] = intval($arParams['ID']);
$arParams['USER_ID'] = intval($arParams['USER_ID']);
 
if($arParams['ID']) {
    $log->view($arParams['ID']);
}

if(!$arParams['USER_ID']) {
    $arResult['EVENTS'] = $log->getMyEvents();
    $arResult['NEW'] = $log->getNewCnt();
} else {
    $arResult['EVENTS'] = $log->getUserEvents($arParams['USER_ID']);
}
 
foreach($arResult['EVENTS'] as $event) {
    $items[] = $event["ITEM_ID"]; 
    $users[] = $event['FROM_USER']; 
} 

$arResult['USERS'] = BitrixHelper::getUsersArrByIds($users);

if(count($items)) {
    CModule::IncludeModule('iblock');
    $res = CIBlockElement::GetList(array(), 
                                   array("IBLOCK_ID" => TASKS_IBLOCK_ID, 'ID' => $items),
                                   false,
                                   false,
                                   array("ID", "IBLOCK_ID", "PROPERTY_PROJECT.DETAIL_PICTURE"));
    while ($task = $res->GetNext()) { 
        if($task["PROPERTY_PROJECT_DETAIL_PICTURE"]) {
            $arr[$task['ID']] = CFile::ResizeImageGet($task["PROPERTY_PROJECT_DETAIL_PICTURE"], array('width'=>45, 'height'=>45), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);  
        }
    } 
    if(count($arr)) {
        foreach($arResult['EVENTS'] as &$event) {
            if($arr[$event["ITEM_ID"]]) {
                $event['PIC'] = $arr[$event["ITEM_ID"]];  
            }
        } 
    }
}


$this->IncludeComponentTemplate();