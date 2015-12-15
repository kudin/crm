<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

CModule::IncludeModule('iblock');

$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "PROPERTY_*");
$arFilter = Array("IBLOCK_ID" => PROJECTS_IBLOCK_ID);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

$userFilter = $USER->GetViewProjectsFilter();
$arFilter = array_merge($userFilter, $arFilter);
 
while ($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields(); 
    $arProps = $ob->GetProperties();
    $arResult['ITEMS'][] = $arFields;
}

$rsUsers = CUser::GetList(($by="NAME"), ($order="ASCS"), array('ACTIVE'=>'Y'), 
                          array('FIELDS'=> array('ID', 'NAME', 'LOGIN', 'LAST_NAME')) );    
while($arUser = $rsUsers->Fetch()) { 
    $arResult['USERS'][] = $arUser;
}
   


$this->IncludeComponentTemplate();