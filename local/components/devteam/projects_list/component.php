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
        $arFields['DETAIL_PICTURE'] = CFile::ResizeImageGet($arFields['DETAIL_PICTURE'], array('width'=>200, 'height'=>60), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);                
    }
    $arResult['ITEMS'][] = $arFields;
}

$arUsersId = array_unique($arUsersId);
$arResult['USERS'] = BitrixHelper::getUsersArrByIds($arUsersId);
$arResult['HAS_RIGHTS_TO_DELETE_PROJECT'] = $USER->hasRigthsToDeleteProject();
$this->IncludeComponentTemplate();