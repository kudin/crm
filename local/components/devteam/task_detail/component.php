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

CModule::IncludeModule('iblock');

$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "PROPERTY_*");
$arFilter = Array("IBLOCK_ID" => PROJECTS_IBLOCK_ID, 'ID' => $arParams['PROJECT']);
$userFilter = $USER->GetViewProjectsFilter();
$arFilter = array_merge($userFilter, $arFilter);

$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
 
if ($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
        $arProps = $ob->GetProperties();
        $arResult['CUSTOMERS_IDS'] = $arProps['CUSTOMER']['VALUE'];
        $arResult['PROGRAMERS_IDS'] = $arProps['PROGRAMMER']['VALUE'];  
        $rsUsers = CUser::GetList(($by="NAME"), ($order="ASCS"), 
                                   array('ACTIVE'=>'Y',
                                         'ID' => implode(' | ', array_unique(array_merge($arResult['CUSTOMERS_IDS'], $arResult['PROGRAMERS_IDS'])))), 
                                   array('FIELDS'=> array('ID', 'NAME', 'LOGIN', 'LAST_NAME')) );    
        while($arUser = $rsUsers->Fetch()) { 
            $arResult['USERS'][$arUser['ID']] = $arUser;
        }  
    $arResult['PROJECT'] = $arFields;
} else { 
    ShowError('Проект не найден');
    return;
} 
 
$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "PROPERTY_*", 'DETAIL_TEXT');
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
} else {
    ShowError('Ошибка доступа к задаче');
    return;
}

$this->IncludeComponentTemplate();