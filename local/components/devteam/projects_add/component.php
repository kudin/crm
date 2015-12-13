<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

if ($_REQUEST['add']) {
    CModule::IncludeModule('iblock');
    $el = new CIBlockElement;
    $name = trim($_REQUEST['name']);
    $description = trim($_REQUEST['description']);

    $arProjectArray = Array(
        "MODIFIED_BY" => $USER->GetID(),
        "IBLOCK_SECTION_ID" => false,
        "IBLOCK_ID" => PROJECTS_IBLOCK_ID,
        "NAME" => $name,
        "DETAIL_TEXT" => $description,
    );
    if ($el->Add($arProjectArray)) {
        ToolTip::Add('Проект "' . $name . '" добавлен');
        LocalRedirect(PROJECTS_LIST_URL);
    } else {
        $arResult['ERROR'] = $el->LAST_ERROR;
    }
} 

$this->IncludeComponentTemplate();
