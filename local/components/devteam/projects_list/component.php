<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

CModule::IncludeModule('iblock');

$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", 
                  "PROPERTY_PROGRAMMER", 'PROPERTY_CUSTOMER',
                  'DETAIL_PICTURE', 'PREVIEW_TEXT');
$arFilter = Array("IBLOCK_ID" => PROJECTS_IBLOCK_ID);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
$userFilter = $USER->GetViewProjectsFilter();
$arFilter = array_merge($userFilter, $arFilter);
 
$arUsersId = array();

while ($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields(); 
    $arProps = $ob->GetProperties();
    $arFields['PROPERTIES'] = $arProps;
    foreach(array('CUSTOMER', 'PROGRAMMER') as $code) {
        if(is_array($arProps[$code]['VALUE'])) {
            $arUsersId = array_merge($arUsersId, $arProps[$code]['VALUE']); 
        }
    }
    if($arFields['DETAIL_PICTURE']) {
        $arFields['DETAIL_PICTURE'] = CFile::ResizeImageGet($arFields['DETAIL_PICTURE'], array('width'=>200, 'height'=>50), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);                
    }
    $arResult['ITEMS'][] = $arFields;
}

$arUsersId = array_unique($arUsersId);

if($arUsersId) { 
    $rsUsers = CUser::GetList(($by="NAME"), ($order="ASCS"), 
                              array('ACTIVE'=>'Y' , 
                                    'ID' => implode(' | ', $arUsersId) ), 
                              array('FIELDS'=> array('ID', 'NAME', 'LOGIN', 'LAST_NAME', 'EMAIL', 'PERSONAL_PHOTO')) );    
    while($arUser = $rsUsers->Fetch()) { 
        if($arUser['PERSONAL_PHOTO']) {
            $arUser['PERSONAL_PHOTO'] = CFile::ResizeImageGet($arUser['PERSONAL_PHOTO'], array('width'=>100, 'height'=>100), BX_RESIZE_IMAGE_PROPORTIONAL, true);       
        } 
        $arResult['USERS'][$arUser['ID']] = $arUser;
    } 
}

$this->IncludeComponentTemplate();