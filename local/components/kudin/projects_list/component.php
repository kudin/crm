<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

CPageOption::SetOptionString("main", "nav_page_in_session", "N");

if($arParams["COUNT"] <= 0) {
    $arParams["COUNT"] = 10;
}

CModule::IncludeModule('iblock');

$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", 
                  "PROPERTY_PROGRAMMER", 'PROPERTY_CUSTOMER',
                  'DETAIL_PICTURE', 'PREVIEW_TEXT');
$arFilter = Array("IBLOCK_ID" => PROJECTS_IBLOCK_ID);
$userFilter = $USER->GetViewProjectsFilter();
$arFilter = array_merge($userFilter, $arFilter);
$res = CIBlockElement::GetList(Array(), $arFilter, false, array('nPageSize' => $arParams['COUNT']), $arSelect);

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
        $arFields['DETAIL_PICTURE'] = CFile::ResizeImageGet($arFields['DETAIL_PICTURE'], array('width'=>45, 'height'=>45), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);                
    }
    $arResult['ITEMS'][] = $arFields;
}
$arResult["NAV_STRING"] = $res->GetPageNavString();

$arUsersId = array_unique($arUsersId);
$arResult['USERS'] = BitrixHelper::getUsersArrByIds($arUsersId);
$arResult['HAS_RIGHTS_TO_ADD_PROJECT'] = $USER->hasRigthsToAddProject();
$this->IncludeComponentTemplate();