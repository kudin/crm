<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
 
$res = CIBlockElement::GetList(Array(), 
        array_merge($USER->GetViewProjectsFilter(), Array("IBLOCK_ID" => PROJECTS_IBLOCK_ID)), 
        false, 
        false,
        array("ID", "IBLOCK_ID", "PROPERTY_*")); 
while ($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    $arProps = $ob->GetProperties();  
    foreach(array('PROGRAMMER', 'CUSTOMER') as $propCode) {
        foreach ($arProps[$propCode]['VALUE'] as $userid) {
            $allUsers[] = $userid;
        } 
    }   
}

$arResult['USERS'] = BitrixHelper::getUsersArrByIds($allUsers);

$this->IncludeComponentTemplate();