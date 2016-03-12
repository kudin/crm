<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$allUsers = array();
$res = CIBlockElement::GetList(Array('SORT' => 'ASC' ,'NAME' => 'ASC'), 
        array_merge($USER->GetViewProjectsFilter(), Array("IBLOCK_ID" => PROJECTS_IBLOCK_ID)), 
        false, 
        false,
        array("ID", "IBLOCK_ID", 'NAME', "PROPERTY_*")); 
while ($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    $arProps = $ob->GetProperties();
    foreach(array('PROGRAMMER', 'CUSTOMER') as $propCode) { 
        foreach ($arProps[$propCode]['VALUE'] as $userid) {
            $allUsers[] = $userid;
            if(!in_array($arFields['ID'], $arResult['USER_TO_PROJECT'][$userid])) {
                $arResult['USER_TO_PROJECT'][$userid][] = $arFields['ID'];
            }
        }
    }
    $arResult['PROJECTS'][] = $arFields;
}

$arResult['USERS'] = BitrixHelper::getUsersArrByIds($allUsers);

$this->IncludeComponentTemplate();