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
    $arParams['DATE_FORMAT'] = 'j F в H:m:s';
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
} else {
    ShowError('Ошибка доступа к задаче');
    return;
}


/* comments */
 
function reCalculateCommentsCnt($itemID) {
      $res = CIBlockElement::GetList(
            array(), 
            array("PROPERTY_TASK" => $itemID, "IBLOCK_ID" => COMMENTS_IBLOCK_ID, "ACTIVE" => "Y"),
            false,
            false,
            array('ID', 'IBLOCK_ID'));
      
      while ($ar_fields = $res->GetNext()) {
          $n++; // ай пока что через пень-колоду 
      } 
      CIBlockElement::SetPropertyValuesEx($itemID, false, array('COMMNETS_CNT' => $n)); 
}
 
if ($_REQUEST['add_comment']) {  
    $_REQUEST['commment'] = strip_tags($_REQUEST['commment']); 
    if (!$_REQUEST['comment']) {
        $arResult['ERROR'] = 'Не введён комментарий';
    } 
    if (!$arResult['ERROR']) { 
        $el = new CIBlockElement; 
        $arLoadProductArray = Array(
            "MODIFIED_BY" => $USER->GetID(),
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID" => COMMENTS_IBLOCK_ID,
            "DATE_ACTIVE_FROM" => ConvertTimeStamp(false, 'FULL'),
            "PROPERTY_VALUES" => array('TASK' => $arParams['ID']),
            "NAME" => TruncateText(strip_tags($_REQUEST['comment']), 200),
            "ACTIVE" => "Y",
            "PREVIEW_TEXT" => TruncateText($_REQUEST['comment'], 10000)
        ); 
        if ($PRODUCT_ID = $el->Add($arLoadProductArray)) { 
            reCalculateCommentsCnt($arParams['ID']);
            LocalRedirect(TASKS_LIST_URL . $arParams['PROJECT'] . '/' . $arParams['ID'] . '/'); // от двойной отправки
        } else {
            $arResult['ERROR'] = $el->LAST_ERROR;
        }
    }
} 
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

$this->IncludeComponentTemplate();