<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arResult = array_pop(BitrixHelper::getUsersArrByIds());

$arResult['IS_ADMIN'] = CUser::IsAdmin();

$this->IncludeComponentTemplate();