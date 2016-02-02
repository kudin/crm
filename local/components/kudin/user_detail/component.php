<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!$arParams['USER_ID']) {
    ShowError('Пользователь не найден');
    return;
}

$arResult['USERS'] = BitrixHelper::getUsersArrByIds($arParams['USER_ID']);

$this->IncludeComponentTemplate();