<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arParams['ID'] = intval($arParams['ID']);
if(!$arParams['ID']) {
    ShowError('Не передан обязательный параметр');
    return;
}

CModule::IncludeModule('iblock');
 
$arFilter = Array("IBLOCK_ID" => PROJECTS_IBLOCK_ID, 
                  "ID" => $arParams['ID']);  
$arFilter = array_merge($USER->GetViewProjectsFilter(), $arFilter); 
$res = CIBlockElement::GetList(array(), 
                               $arFilter, 
                               false, 
                               false, 
                               array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "PROPERTY_PROGRAMMER", 'PROPERTY_CUSTOMER', 'DETAIL_PICTURE', 'PREVIEW_TEXT'));
$arUsersId = array();
if ($ob = $res->GetNextElement()) {
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
    $arResult['PROJECT'] = $arFields;
} else {
    $arResult['ERROR'] = 'Проект не найден или доступ к нему запрещён';
}

$arUsersId = array_unique($arUsersId);
$arResult['USERS'] = BitrixHelper::getUsersArrByIds($arUsersId);

$this->IncludeComponentTemplate();