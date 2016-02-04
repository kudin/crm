<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

CModule::IncludeModule('iblock');

$res = CIBlockElement::GetList(array(),
                               array("IBLOCK_ID" => TASKS_IBLOCK_ID,
                                     'ACTIVE' => 'Y',
                                     "PROPERTY_PROGRAMMER" => CUser::GetID(),
                                     "PROPERTY_STATUS" => STATUS_LIST_WORK), 
                               false, 
                               false,
                               array('ID', 'NAME', 'PROPERTY_PROJECT', 'PROPERTY_STATUS_DATE'));
if ($arResult = $res->GetNext()) {
    $arResult['DETAIL_PAGE_URL'] = TASKS_LIST_URL . $arResult["PROPERTY_PROJECT_VALUE"] . '/' . $arResult['ID'] . '/';
    $date = new DateTime($arResult["PROPERTY_STATUS_DATE_VALUE"]); 
    $arResult['DATE'] = $date->format('D M d Y H:i:s O');
    $curdate = new DateTime();
    $diff = $date->diff($curdate); 
    $arResult['TIME'] = $diff->format('%H:%I:%S');
}

$this->IncludeComponentTemplate();