<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
 
$rsUsers = CUser::GetList(($by="NAME"), ($order="ASCS"), 
                          array('ACTIVE'=>'Y'), 
                          array('FIELDS'=> array('ID', 'NAME', 'LOGIN', 'LAST_NAME')) );    
while($arUser = $rsUsers->Fetch()) {
    $arResult['USERS'][] = $arUser;
}
 
if ($_REQUEST['add']) { 
    CModule::IncludeModule('iblock');
    $el = new CIBlockElement;
    $name = trim($_REQUEST['name']);
    $description = trim($_REQUEST['description']);

    $arProjectArray = Array(
        "PROPERTY_VALUES" => array(
            'CUSTOMER' => $_REQUEST['CUSTOMER'],
            'PROGRAMMER' => $_REQUEST['PROGRAMMER'],
            'URL' => trim($_REQUEST['url'])
        ),
        "MODIFIED_BY" => $USER->GetID(),
        "IBLOCK_SECTION_ID" => false,
        "IBLOCK_ID" => PROJECTS_IBLOCK_ID,
        "NAME" => $name,
        "PREVIEW_TEXT" => $description,
        "DETAIL_PICTURE" => $_FILES['logo'], 
    );
    if ($el->Add($arProjectArray)) {
        ToolTip::Add('Проект "' . $name . '" добавлен');
        LocalRedirect(PROJECTS_LIST_URL);
    } else {
        $arResult['ERROR'] = $el->LAST_ERROR;
    } 
} 

$this->IncludeComponentTemplate();