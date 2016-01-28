<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!$arParams['ID']) {
    ShowError('Пользователь не найден');
    return;
}

$this->IncludeComponentTemplate();