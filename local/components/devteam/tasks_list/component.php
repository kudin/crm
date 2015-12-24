<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
 
if(!$arParams["PROJECT"]) {
    ShowError('Не передан обязательный параметр');
    return;
}

CModule::IncludeModule('iblock');

if(!$arParams['DATE_FORMAT']) {
    $arParams['DATE_FORMAT'] = 'j F Y';
}

$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "PROPERTY_*");
$arFilter = Array("IBLOCK_ID" => PROJECTS_IBLOCK_ID);
$userFilter = $USER->GetViewProjectsFilter();
$arFilter = array_merge($userFilter, $arFilter);

$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
 
while ($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields(); 
    if($arParams["PROJECT"] == $arFields['ID']) {
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
    } 
    $arResult['PROJECTS'][$arFields['ID']] = $arFields;
}

$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "PROPERTY_*", "DATE_CREATE");
$arFilter = Array("IBLOCK_ID" => TASKS_IBLOCK_ID, 'ACTIVE' => 'Y', 'PROPERTY_PROJECT' => $arParams["PROJECT"]);
//$userFilter = $USER->GetViewTasksFilter();
//$arFilter = array_merge($userFilter, $arFilter);

$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
 
while ($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    if (strlen($arFields["DATE_CREATE"]) > 0) {
        $arFields["DATE_CREATE"] = CIBlockFormatProperties::DateFormat($arParams['DATE_FORMAT'], MakeTimeStamp($arFields["DATE_CREATE"], CSite::GetDateFormat()));
    }
    $arResult['TASKS'][] = $arFields;
}

$this->IncludeComponentTemplate();