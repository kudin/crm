<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$log = new CrmLog();
$arResult['EVENTS'] = $log->getMyEvents();

foreach($arResult['EVENTS'] as $event) {
    $users[] = $event['USER'];
}
$arResult['USERS'] = BitrixHelper::getUsersArrByIds($users);

$this->IncludeComponentTemplate();