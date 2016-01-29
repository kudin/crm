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
    $users[] = $event['USER'];
}
$arResult['USERS'] = BitrixHelper::getUsersArrByIds($users);

$this->IncludeComponentTemplate();