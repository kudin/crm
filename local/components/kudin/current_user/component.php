<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
 
$arResult = array_pop(BitrixHelper::getUsersArrByIds(array(CUser::GetID())));

$arResult['IS_ADMIN'] = CUser::IsAdmin();

$this->IncludeComponentTemplate();