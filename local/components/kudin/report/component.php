<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$allUsers = array();

CModule::IncludeModule('iblock');
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
    $arResult['PROJECTS'][$arFields['ID']] = $arFields;
}

$arParams['USER_ID'] = CUser::GetID();

$arResult['USERS'] = BitrixHelper::getUsersArrByIds($allUsers);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $arResult['IS_REPORT'] = true;
    $projects = $_REQUEST['projects'];
    $access = true;
    $arResult['USER'] = $_REQUEST['user']; 
    foreach($projects as $project) {
        if(!in_array($project, $arResult['USER_TO_PROJECT'][$arResult['USER']])) {
            $access = false; 
            break;
        }
    }
    $arResult['RESERVATION'] = $_POST["reservation"];
    $dates = explode(' - ', $arResult['RESERVATION']); 
    if($access && count($dates) == 2) {
        $dateFrom = new DateTime($dates[0]);
        $dateTo = new DateTime($dates[1]);    
        $res = CIBlockElement::GetList(Array('DATE_CREATE' => 'ASC'), 
            array('IBLOCK_ID' => TRACKING_IBLOCK_ID, 
                  'CREATED_BY' => $arResult['USER'],
                  '>=DATE_CREATE' => $dateFrom->format("d.m.Y 00:00:00"),
                  '<=DATE_CREATE' => $dateTo->format("d.m.Y 23:59:59")
                ),
            false, 
            false,
            array("ID", "IBLOCK_ID", "DATE_CREATE", "NAME", "PROPERTY_HOURS", 'PROPERTY_TASK')); 
        while ($row = $res->Fetch()) {
            $tasks[] = $row["PROPERTY_TASK_VALUE"];
            $timers[$row["PROPERTY_TASK_VALUE"]][] = $row;
        }
        $tasks = array_unique($tasks);
        if(count($tasks) && count($projects)) {
            $arResult['ALLSUMM'] = 0;
            $res = CIBlockElement::GetList(
                array(), 
                array('IBLOCK_ID' => TASKS_IBLOCK_ID,
                      'ID' => $tasks, 
                      'PROPERTY_PROJECT' => $projects),
                false, 
                false,
                array("ID", "NAME", "PROPERTY_PROJECT")); 
            while($task = $res->Fetch()) {
                $time = 0;
                $names = array();
                foreach($timers[$task['ID']] as $timer) {
                    $time += $timer['PROPERTY_HOURS_VALUE'];
                    $names[] = $timer['NAME'];
                }
                $arResult['ALLSUMM'] += $time;
                $task['TIME'] = $time;
                $task['TIME_NAME'] = implode(', ', $names);
                $arResult['TASKS'][$task['PROPERTY_PROJECT_VALUE']][] = $task;
            }
        }
    }
}

$this->IncludeComponentTemplate();