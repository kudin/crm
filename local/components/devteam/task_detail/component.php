<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
 
foreach(array('ID', 'PROJECT') as $code) {
    if(!$arParams[$code]) {
        ShowError('Не передан обязательный параметр');
        return;
    }
}

if(!$USER->hasRightsToViewTask($arParams["ID"])){
    ShowError('У Вас нет прав на просмотр этой задачи');
    return;
}
 
if(!$arParams['DATE_FORMAT']) {
    $arParams['DATE_FORMAT'] = 'j F в H:m';
}

CModule::IncludeModule('iblock');


/* project */

$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "CREATED_BY");
$arFilter = Array("IBLOCK_ID" => PROJECTS_IBLOCK_ID, 'ID' => $arParams['PROJECT']);
$userFilter = $USER->GetViewProjectsFilter();
$arFilter = array_merge($userFilter, $arFilter); 
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect); 
if ($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    $arProps = $ob->GetProperties(); 
    $arResult['CUSTOMERS_IDS'] = $arProps['CUSTOMER']['VALUE'];
    $arResult['PROGRAMERS_IDS'] = $arProps['PROGRAMMER']['VALUE'];
    $arResult['PROJECT'] = $arFields;
} else { 
    ShowError('Проект не найден');
    return;
} 


/* tasks */

$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "PROPERTY_*", 'DETAIL_TEXT', "DATE_CREATE");
$arFilter = Array(
    "IBLOCK_ID" => TASKS_IBLOCK_ID,
    'ACTIVE' => 'Y',  
    'ID' => $arParams['ID']);
$userFilter = $USER->GetViewTasksFilter();
$arFilter = array_merge($userFilter, $arFilter); 
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect); 
if ($ob = $res->GetNextElement()) {
    $arResult['TASK'] = $ob->GetFields();
    $arResult['TASK']['PROPS'] = $ob->GetProperties();
    if($arResult['TASK']['PROPS']['FILES']['VALUE']) { 
        foreach($arResult['TASK']['PROPS']['FILES']['VALUE'] as $fileId) {
            $files[] = CFile::GetFileArray($fileId);
        }
        $arResult['TASK']['PROPS']['FILES']['VALUE'] = $files;
    }
    if (strlen($arResult['TASK']["DATE_CREATE"]) > 0) {
        $arResult['TASK']["DATE_CREATE"] = CIBlockFormatProperties::DateFormat($arParams['DATE_FORMAT'], MakeTimeStamp($arResult['TASK']["DATE_CREATE"], CSite::GetDateFormat()));
    }
    $arResult['STATUS'] = $arResult['TASK']['PROPS']['STATUS']["VALUE_ENUM_ID"]; 
    $arResult['STATUS_TEXT'] = StatusHelper::getStr($arResult['STATUS']);
} else {
    ShowError('Ошибка доступа к задаче');
    return;
}


/* comments */

if ($_REQUEST['add_comment']) {
    $_REQUEST['commment'] = strip_tags($_REQUEST['commment']); 
    if (!$_REQUEST['comment']) {
        $arResult['ERROR'] = 'Не введён комментарий';
    } 
    if (!$arResult['ERROR']) {
        $el = new CIBlockElement;
        if ($PRODUCT_ID = $el->Add(
            array("MODIFIED_BY" => $USER->GetID(),
                  "IBLOCK_SECTION_ID" => false,
                  "IBLOCK_ID" => COMMENTS_IBLOCK_ID,
                  "DATE_ACTIVE_FROM" => ConvertTimeStamp(false, 'FULL'),
                  "PROPERTY_VALUES" => array('TASK' => $arParams['ID']),
                  "NAME" => TruncateText(strip_tags($_REQUEST['comment']), 180),
                  "ACTIVE" => "Y",
                  "PREVIEW_TEXT" => TruncateText($_REQUEST['comment'], COMMENT_MAX_LENGHT)))) {
            LocalRedirect(TASKS_LIST_URL . $arParams['PROJECT'] . '/' . $arParams['ID'] . '/');
        } else {
            $arResult['ERROR'] = $el->LAST_ERROR;
        }
    }
}

$created_by = array();
$res = CIBlockElement::GetList(
    array("DATE_ACTIVE_FROM" => "DESC"), 
    array("PROPERTY_TASK" => $arParams['ID'], 
          "IBLOCK_ID" => COMMENTS_IBLOCK_ID, 
          "ACTIVE" => "Y"), 
    false,
    false,  
    array('DATE_ACTIVE_FROM', 'PREVIEW_TEXT', 'CREATED_BY', 'ID', 'IBLOCK_ID', 'DATE_CREATE')); 
while ($ar_fields = $res->GetNext()) {  
    $created_by[] = $ar_fields['CREATED_BY'];
    $arResult['COMMENTS'][] = $ar_fields;
}


/* users */

$usersIds = array_merge($arResult['CUSTOMERS_IDS'], $arResult['PROGRAMERS_IDS'], $created_by);
$arResult['USERS'] = BitrixHelper::getUsersArrByIds($usersIds);  


/* rights */

if(in_array($USER->GetID(), $arResult['PROGRAMERS_IDS'])) {
    $arResult['IS_PROGRAMMER'] = true;
} else { // если программист ставит задачу сам себе
    if(in_array($USER->GetID(), $arResult['CUSTOMERS_IDS'])) {
        $arResult['IS_CUSTOMER'] = true;
    }
}
            

/* actions */

if($action = $_REQUEST['action']) {
    if($arResult['IS_PROGRAMMER']) {
        switch ($action) {
            case 'closeTask':
                if(($arResult['STATUS'] == STATUS_LIST_COMPLETE) && ($arResult['PROGRAMERS_IDS'] == $arResult['CUSTOMERS_IDS'])) {
                    $newStatus = STATUS_LIST_ACCEPT;
                }
                break;
            case 'start':
                if(($arResult['STATUS'] == STATUS_LIST_AGR_CALCED) && ($arResult['PROGRAMERS_IDS'] == $arResult['CUSTOMERS_IDS'])) {
                    $newStatus = STATUS_LIST_WORK;
                } 
                if(in_array($arResult['STATUS'], array(STATUS_LIST_CALC_AGRED, STATUS_LIST_PAUSE, STATUS_LIST_COMPLETE))) {
                    $newStatus = STATUS_LIST_WORK;
                }
                break; 
            case 'stop':
                if($arResult['STATUS'] == STATUS_LIST_WORK) {
                    $newStatus = STATUS_LIST_PAUSE;
                }
                break;
            case 'complete':
                if(in_array($arResult['STATUS'], array(STATUS_LIST_WORK, STATUS_LIST_PAUSE))) {
                    $newStatus = STATUS_LIST_COMPLETE;
                } 
                break;
            case 'docalc':  
                if($arResult['STATUS'] == false) { 
                    if($time = formatTime($_REQUEST['time'])) { 
                        CIBlockElement::SetPropertyValuesEx($arParams['ID'], TASKS_IBLOCK_ID, array('CALC' => $time));
                        $newStatus = STATUS_LIST_AGR_CALCED;
                    } else {
                        ToolTip::Add('Введено некорректное значение оценки');
                    }
                }
                break;
            default:
                break;
        }
    } elseif($arResult['IS_CUSTOMER']) {
        switch ($action) { 
            default:
                break;
        } 
    }
    if($newStatus) {
        CIBlockElement::SetPropertyValuesEx($arParams['ID'], TASKS_IBLOCK_ID, array('STATUS' => $newStatus));
    }
    LocalRedirect($APPLICATION->GetCurDir());
} 


$this->IncludeComponentTemplate();